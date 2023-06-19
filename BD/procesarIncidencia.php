<?php
/**
 * Fichero con las funciones relacionadas con las incidencias.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

/**
 * Función para insertar una incidencia en la base de datos, tras el procesamiento de un formulario.
 * 
 * @global array $erroresIncidencia Array que contiene los errores de validación del formulario.
 * @global bool $confirmada Indica si la inserción de la incidencia ha sido confirmada.
 * @global bool $insertada Indica si la inserción de la incidencia ha sido realizada.
 * 
 * @return void
 */
function insertarIncidencia()
{
    global $erroresIncidencia;
    global $confirmada;
    global $insertada;

    // Verificar si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['titulo'])) {
            $titulo = strip_tags($_POST['titulo']);
            $titulo = htmlentities($titulo, ENT_QUOTES);
        }

        if (isset($_POST['descripcion'])) {
            $descripcion = strip_tags($_POST['descripcion']);
            $descripcion = htmlentities($descripcion, ENT_QUOTES);
        }

        if (isset($_POST['lugar'])) {
            $lugar = strip_tags($_POST['lugar']);
            $lugar = htmlentities($lugar, ENT_QUOTES);
        }

        if (isset($_POST['keywords'])) {
            $keywords = $_POST['keywords'];
        }

        $estado = "Pendiente";

        if (isset($_POST['enviar'])) {
            // - - - Validamos los datos - - - 
            if (empty($titulo)) {
                $erroresIncidencia['titulo'] = "El nombre no puede estar vacío";
            }
            if (empty($descripcion)) {
                $erroresIncidencia['descripcion'] = "La descripción no puede estar vacía";
            }

            if (empty($lugar)) {
                $erroresIncidencia['lugar'] = "El lugar no puede estar vacío";
            }

            // Si no hay errores, procesamos los datos.
        } else if (isset($_POST['confirmar'])) {
            // Conectar a la base de datos
            $db = conexion();

            // Insercion en la tabla incidencias.
            $sql = "INSERT INTO incidencias (titulo, descripcion, lugar, keywords, fecha, idusuario, estado) VALUES (?, ?, ?, ?, NOW(), ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ssssss", $titulo, $descripcion, $lugar, $keywords, $_SESSION['idUsuario'], $estado);

            $nombreUsuario = $_SESSION['nombreUsuario'];

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $_SESSION['nuevaIncidencia'] = $db->insert_id;

                insertarLog("El usuario $nombreUsuario ha insertado una nueva incidencia", $db);
                $insertada = true;
            } else {
                $insertada = false;
            }

            // Cerrar la conexión con la base de datos
            $stmt->close();
            desconexion($db);
        }

        if (count($erroresIncidencia) == 0) {
            $confirmada = true;
        }

    }
}


/**
 * Función para borrar una foto de una incidencia de la base de datos.
 * 
 * @param int $idFoto ID de la foto a borrar.
 * 
 * @return void
 */
function borrarFoto($idFoto)
{
    $db = conexion();

    $sql = "DELETE FROM fotos WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $idFoto);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Foto borrada con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ha ocurrido un error al borrar la foto.";
    }

    $stmt->close();
    desconexion($db);
}

/**
 * Función para procesar el formulario de editar una incidencia.
 * 
 * @return void
 */
function procesamientoEditar()
{
    //Código para procesar el formulario de estado de la incidencia
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificarEstado'])) {
        if ($_SESSION['rol'] == "admin") {
            $db = conexion();
            $id = $_POST['idIncidencia'];
            $estado = $_POST['estado'];
            $sql = "UPDATE incidencias SET estado = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("si", $estado, $id);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Insertar en el log
                insertarLog("Se ha modificado el estado de la incidencia correctamente", $db);

                // Mensaje de correcto
                $_SESSION['mensaje'] = "¡Enhorabuena! La información ha sido modificada con éxito.";
                $stmt->close();

                // Redirigimos.
                header('Location: index.php');
                exit;
            } else {
                $_SESSION['mensaje'] = "Lo sentimos... No hemos podido modificar los datos de la incidencia.";
                $stmt->close();
            }
            desconexion($db);
        }
    }

    //Código para procesar el formulario de editar incidencia
    global $erroresIncidencia;
    global $confirmada;

    if (isset($_POST['editarInc'])) {
        $confirmada = false;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['editar']) || isset($_POST['confirmar'])) && !isset($_POST['editarInc'])) {
        $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
        $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
        $lugar = isset($_POST['lugar']) ? $_POST['lugar'] : '';
        $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';


        // - - - Validamos los datos - - - 
        if (empty($titulo)) {
            $erroresIncidencia['titulo'] = "El nombre no puede estar vacío";
        }
        if (empty($descripcion)) {
            $erroresIncidencia['descripcion'] = "La descripción no puede estar vacía";
        }

        if (empty($lugar)) {
            $erroresIncidencia['lugar'] = "El lugar no puede estar vacío";
        }

        // Si no hay errores, procesamos los datos.
        if (count($erroresIncidencia) == 0) {
            $confirmada = true;
            if (isset($_POST['confirmar'])) {
                $db = conexion();
                $id = $_POST['idIncidencia'];
                $sql = "UPDATE incidencias SET titulo = ?, descripcion = ?, lugar = ?, keywords = ? WHERE id = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("ssssi", $titulo, $descripcion, $lugar, $keywords, $id);

                // Ejecutar la consulta
                if ($stmt->execute()) {
                    // Insertar en el log
                    insertarLog("Se ha modificado la incidencia correctamente", $db);

                    // Mensaje de correcto
                    $_SESSION['mensaje'] = "¡Enhorabuena! La información ha sido modificada con éxito.";
                    $stmt->close();

                    // Redirigimos.
                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION['mensaje'] = "Lo sentimos... No hemos podido modificar los datos de la incidencia.";
                    $stmt->close();
                }
                desconexion($db);
            }
        }
    }


    // Código para procesar el formulario para subir fotos y borrar fotos
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrarFoto'])) {
        $id = $_POST['idFoto'];
        borrarFoto($id);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subir']) && isset($_FILES['images'])) {
        $db = conexion();
        $idIncidencia = $_POST['idIncidencia'];
        $image = file_get_contents($_FILES['images']['tmp_name']);
        $query = "INSERT INTO fotos (foto, idIncidencia) VALUES(?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $image, $idIncidencia);
        $stmt->execute();
        //subirFotoIncidencia("fotos", $db, $idIncidencia, $image);
        desconexion($db);
    }
}


?>