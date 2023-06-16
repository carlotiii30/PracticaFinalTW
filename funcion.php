<?php
/**
 * Fichero con funciones generales para diferentes archivos
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

/**
 * Función para insertar una fila a la tabla de log.
 * 
 * @param string $accion Acción que se va a insertar en la tabla.
 * @param mysqli $db Conexión a la base de datos.
 */
function insertarLog($accion, $db)
{
    $accion = "INFO: " . $accion; // Concatenar "INFO: " al inicio de $accion
    $sql = "INSERT INTO logs (fecha, accion)
                    VALUES (NOW(), ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $accion); // Pasar el valor de $accion concatenado
    $stmt->execute();
    $stmt->close();
}

/**
 * Función para subir una foto a la base de datos.
 * 
 * @param string $tabla Nombre de la tabla a la que se va a insertar la foto.
 * @param mysqli $db Conexión a la base de datos.
 * @param int $id ID del usuario o incidencia a la que se le va a insertar la foto.
 * 
 * @return bool Retorna true si se ha subido la foto correctamente, false en caso contrario.
 */
 function subirFoto($tabla, $db, $id)
{
    $image = $_SESSION['imagen'];
    $query = "UPDATE $tabla SET foto = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('si', $image, $id);

    if ($stmt->execute())
        return true;
    else
        return false;
}

/**
 * Función para subir una foto de una incidencia a la base de datos.
 * 
 * @param string $tabla Nombre de la tabla a la que se va a insertar la foto.
 * @param mysqli $db Conexión a la base de datos.
 * @param int $id ID del usuario o incidencia a la que se le va a insertar la foto.
 * @param string $image Imagen a insertar.
 * 
 * @return bool Retorna true si se ha subido la foto correctamente, false en caso contrario.
 */
function subirFotoIncidencia($tabla, $db, $id, $image)
{
    $query = "INSERT INTO $tabla (foto, IdIncidencia) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('si', $image, $id);

    if ($stmt->execute())
        return true;
    else
        return false;
}



/**
 * Función para descargar una foto de un usuario. La función imprime la foto directamente.
 * 
 * @param string $tabla Nombre de la tabla de la que se va a descargar la foto.
 * @param int $idUsuario ID del usuario del que se va a descargar la foto.
 * @param mysqli $db Conexión a la base de datos.
 */
function descargarFoto($tabla, $idUsuario, $db)
{
    $foto = null;
    $query = "SELECT foto FROM $tabla WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $stmt->bind_result($foto);

    $imageData = '';
    if ($stmt->fetch()) {
        $fotoData = base64_encode($foto);
        $src = 'data:image/jpeg;base64,' . $fotoData;
        $imageData = "<img src='$src' alt='Foto'>";
    } else {
        $imageData = "Foto no encontrada.";
    }

    $stmt->close();

    echo $imageData;
}

/**
 * Función para valorar una incidencia.
 * 
 * @param int $incidencia ID de la incidencia a valorar.
 * @param string $accion Acción a realizar (sumar o restar).
 */
function valoracion($incidencia, $accion)
{
    //¿Cómo se supone que vota el visitante?
    $db = conexion();
    $idIncidencia = $incidencia;
    $valoracion = null;
    if (isset($_SESSION['idUsuario'])) {
        $idUsuario = $_SESSION['idUsuario'];
        $nombre = $_SESSION['nombreUsuario'];
    }

    if ($accion == "sumar") {
        $valoracion = 1;
        $mensaje = "El usuario $nombre ha valorado positivamente la incidencia $idIncidencia.";
        $confirmacion = "¡Wow! Parece que está de acuerdo con una incidencia. ¡Qué de apoyo hay en la comunidad!";
    } else if ($accion == "restar") {
        $valoracion = 0;
        $mensaje = "El usuario $nombre ha valorado negativamente la incidencia $idIncidencia.";
        $confirmacion = "Oh oh... ¿Hay algún problema con esa incidencia valorada negativamente? Deje un comentario para expresar su opinión.";
    }

    if (isset($_SESSION['idUsuario'])) {
        $sql = "INSERT INTO valoraciones (valoracion, idIncidencia, idUsuario)
                VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $valoracion, $idIncidencia, $_SESSION['idUsuario']);
    } else {
        $sql = "INSERT INTO valoraciones (valoracion, idIncidencia)
                VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $valoracion, $idIncidencia);
    }

    $resultado = $stmt->execute();

    if ($resultado) {
        $_SESSION['mensaje'] = $confirmacion;
        insertarLog($mensaje, $db);
        header("Location: index.php");
        exit;
    }

}

/**
 * Función para obtener el nombre de usuario a partir de la id de la tabla de incidencias.
 * 
 * @param int $idUsuario ID del usuario del que se va a obtener el nombre.
 * 
 * @return string Nombre del usuario.
 */
function obtenerNombreUsuario($idUsuario)
{
    $db = conexion();

    $sql = "SELECT nombre, apellidos FROM usuarios WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombreUsuario = $row['nombre'];
        $apellidos = $row['apellidos'];
    } else {
        $nombreUsuario = 'Usuario no encontrado';
        $apellidos = '';
    }

    desconexion($db);

    return $nombreUsuario . ' ' . $apellidos;

}

/**
 * Función para obtener las valoraciones a partir de la id de la tabla de incidencias.
 * 
 * @param int $idIncidencia ID de la incidencia de la que se van a obtener las valoraciones.
 * 
 * @return array Array con las valoraciones positivas y negativas.
 */
function obtenerValoraciones($idIncidencia)
{
    $db = conexion();

    $sql = "SELECT SUM(CASE WHEN valoracion = 1 THEN 1 ELSE 0 END) AS positivas,
                    SUM(CASE WHEN valoracion = 0 THEN 1 ELSE 0 END) AS negativas
            FROM valoraciones
            WHERE idIncidencia = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $idIncidencia);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $valoraciones = $resultado->fetch_assoc();
    } else {
        $valoraciones['positivas'] = 0;
        $valoraciones['negativas'] = 0;
    }

    desconexion($db);

    return $valoraciones;
}

/**
 * Función para comprobar si un usuario o visitante puede valorar una incidencia.
 * 
 * @param int $idIncidencia ID de la incidencia a comprobar.
 * 
 * @return bool Retorna true si el usuario o visitante puede valorar la incidencia, false en caso contrario.
 */
function puedeValorar($idIncidencia)
{
    $puedeVotar = false;

    if (isset($_SESSION["autenticado"]) && isset($_SESSION["idUsuario"])) {
        $idUsuario = $_SESSION["idUsuario"];

        $db = conexion();

        $sql = "SELECT * FROM valoraciones WHERE idUsuario = ? AND idIncidencia = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $idUsuario, $idIncidencia);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 0) {
            $puedeVotar = true;
        }

        desconexion($db);
    } else {
        $nombreCookie = "VotacionIncidencia_" . $idIncidencia;
        if (!isset($_COOKIE[$nombreCookie])) {
            setcookie($nombreCookie, "Ha votado la incidencia", 0);
            $puedeVotar = true;
        }
    }

    return $puedeVotar;

}


/**
 * Función para borrar una incidencia
 * 
 * @param int $id ID de la incidencia a borrar.
 */
function borrarIncidencia($id)
{
    $db = conexion();

    $sql = "DELETE FROM incidencias WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        insertarLog("La incidencia $id ha sido eliminada.", $db);
        $_SESSION['mensaje'] = "La incidencia seleccionada ha sido eliminada satisfactoriamente. Esperemos que no haya sido un error, porque si no...";
    }
    
    desconexion($db);
    
    header('Location: index.php');
    exit;
    
}

/**
 * Función para borrar un usuario
 * 
 * @param int $id ID del usuario a borrar.
 * @param mysqli $db Conexión a la base de datos.
 */
function borrarUsuario($id, $db)
{
    // Borramos todas las incidencias relacionadas con el usuario
    $sql = "DELETE FROM incidencias WHERE idUsuario = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Borramos todos los comentarios relacionados con el usuario
    $sql = "DELETE FROM comentarios WHERE idUsuario = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Borramos al usuario
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        insertarLog("El usuario $id ha sido eliminado.", $db);
        $_SESSION['mensaje'] = "El usuario seleccionado ha sido eliminado. ¡¿Qué habrá hecho?!";
    } else {
        $_SESSION['mensaje'] = "El usuario seleccionado no se ha podido borrar. Parece que tiene una segunda oportunidad.";
    }
    
    header('Location: index.php');
    exit;
}

/**
 * Función para borrar un comentario
 * 
 * @param int $id ID del comentario a borrar.
 */
function borrarComentario($id)
{
    $db = conexion();

    $sql = "DELETE FROM comentarios WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        insertarLog("El comentario $id ha sido eliminado.", $db);
        $_SESSION['mensaje'] = "El comentario seleccionado ha sido eliminado. Espero que no lo haya leído nadie...";
    } else {
        $_SESSION['mensaje'] = "El comentario seleccionado no se ha podido eliminar. Puedes volver a intentarlo.";
    }
    
    desconexion($db);
    
    header('Location: index.php');
    exit;
    
}

?>