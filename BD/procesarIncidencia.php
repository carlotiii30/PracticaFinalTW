<?php
require('baseDatos.php'); // Conexión y desconexión
require('../funcion.php');

session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados del formulario
    $hayImagen = !empty($_FILES['images']['name']) ? true : false;
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $lugar = isset($_POST['lugar']) ? $_POST['lugar'] : '';
    $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
    $estado = "pendiente";

    // - - - Validamos los datos - - - 
    if (empty($titulo)) {
        $errores['titulo'] = "El nombre no puede estar vacío";
    }
    if (empty($descripcion)) {
        $errores['descripcion'] = "La descripción no puede estar vacía";
    }

    if (empty($lugar)) {
        $errores['lugar'] = "El lugar no puede estar vacío";
    }

    // Si no hay errores, procesamos los datos.
    if (count($errores) === 0) {

        // Conectar a la base de datos
        $db = conexion();

        // Insercion en la tabla incidencias.
        $sql = "INSERT INTO incidencias (titulo, descripcion, lugar, keywords, fecha, idusuario, estado) VALUES (?, ?, ?, ?, NOW(), ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssss", $titulo, $descripcion, $lugar, $keywords, $_SESSION['idUsuario'], $estado);

        $nombreUsuario = $_SESSION['nombreUsuario'];

        // Ejecutar la consulta
        if ($stmt->execute()) {
            insertarLog("El usuario $nombreUsuario ha insertado una nueva incidencia", $db);
            $_SESSION['mensaje'] = "¡Enhorabuena! Ha creado una nueva incidencia con éxito."; // Guardar el mensaje en la variable de sesión
        } else {
            $_SESSION['mensaje'] = "Lo sentimos... No hemos podido añadir tu incidencia. Inténtelo de nuevo."; // Guardar el mensaje de error en la variable de sesión
        }

        // Cerrar la conexión con la base de datos
        $stmt->close();
        desconexion($db);

        // Redirigir a la página principal
        header('Location: ../index.php');
        exit;
    }
}
?>