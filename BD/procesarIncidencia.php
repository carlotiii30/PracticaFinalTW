<?php
require('baseDatos.php'); // Conexión y desconexión
require('../funcion.php');

session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $lugar = $_POST['lugar'];
    $keywords = $_POST['keywords'];

    // Realizar validaciones de los datos si es necesario

    // Conectar a la base de datos
    $db = conexion();

    // Preparar la consulta para insertar los datos en la tabla incidencias
    $sql = "INSERT INTO incidencias (titulo, descripcion, lugar, keywords, fecha) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssss", $titulo, $descripcion, $lugar, $keywords);

    $nombreUsuario = $_SESSION['nombreUsuario'];
    // Ejecutar la consulta
    if ($stmt->execute()) {
        insertarLog("El usuario $nombreUsuario ha insertado una nueva incidencia", $db);
    } else {
        echo "Ocurrió un error al insertar los datos.";
    }

    // Cerrar la conexión con la base de datos
    $stmt->close();
    desconexion($db);
}
?>