<?php

function insertarIncidencia()
{
    global $erroresIncidencia;
    global $confirmada;
    global $insertada;

    // Verificar si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos enviados del formulario
        $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
        $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
        $lugar = isset($_POST['lugar']) ? $_POST['lugar'] : '';
        $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
        $estado = "pendiente";

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


function agregarFotoIncidencia()
{
    // Verificar si se ha enviado una foto
    if (!empty($_FILES['images']['name'])) {
        $db = conexion();

        $image = file_get_contents($_FILES['images']['tmp_name']);
        $idIncidencia = $_SESSION["nuevaIncidencia"];

        if (subirFotoIncidencia("fotos", $db, $idIncidencia, $image)) {
            $_SESSION['mensaje'] = "Nueva incidencia registrada con foto.";
        } else {
            $_SESSION['mensaje'] = "Hemos registrado una nueva incidencia, pero ha ocurrido un error al guardar la foto.";
        }

        desconexion($db);
    }else{
        $_SESSION['mensaje'] = "Se lo salta todo";
    }
    
    // Redirigir al index
    header('Location: index.php');
    exit;
}

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

function procesamientoEditar(){
    //Aquí debería ir el resto de código de editarIncidencia.php
    
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrarFoto'])){
        $id = $_POST['idFoto'];
        borrarFoto($id);
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subir']) && isset($_FILES['images'])){
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