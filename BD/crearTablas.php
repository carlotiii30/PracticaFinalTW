<?php

// Datos de la conexión
$host = "localhost";
$usuario = "carlotadlavega2223";
$clave = "nQ69ZPy3";
$bbdd = "carlotadlavega2223";

// Conexión
$db = new mysqli($host, $usuario, $clave, $bbdd);

if ($db) {
	echo "<p>Conexión con éxito</p>";

	// Borrar tabla anterior si existe
	//$sql = "DROP TABLE IF EXISTS usuarios";

	// Ejecutar la consulta
	/*if ($db->query($sql) === TRUE) {
		echo "Tabla anterior eliminada";
	} else {
		echo "Error al eliminar tabla anterior: " . $db->error;
		exit;
	}*/

	// Crear la tabla con la columna de foto
	/*$sql = "CREATE TABLE usuarios (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(50) DEFAULT NULL,
            apellidos VARCHAR(100) DEFAULT NULL,
            email VARCHAR(100) DEFAULT NULL,
            password VARCHAR(255) DEFAULT NULL,
            telefono VARCHAR(11) DEFAULT NULL,
            direccion VARCHAR(100) DEFAULT NULL,
            estado CHAR(30) DEFAULT NULL,
            rol VARCHAR(15) DEFAULT NULL,
            foto LONGBLOB
            )";*/
	
	$sql = "CREATE TABLE incidencias (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(50) DEFAULT NULL,
            descripcion text DEFAULT NULL,
            fecha datetime DEFAULT NULL,
            lugar VARCHAR(50) DEFAULT NULL,
            keywords VARCHAR(30) DEFAULT NULL,
            idusuario INT(10) DEFAULT NULL,
            estado CHAR(30) DEFAULT NULL,
            )";

	// Ejecutar la consulta
	if ($db->query($sql) === TRUE) {
		echo "Tabla creada correctamente";
	} else {
		echo "Error al crear tabla: " . $db->error;
		exit;
	}

	mysqli_close($db);
} else {
	echo "<p>Error de conexión</p>";
	echo "<p>Código: " . mysqli_connect_errno() . "</p>";
	echo "<p>Mensaje: " . mysqli_connect_error() . "</p>";
	die("Adiós");
}

?>