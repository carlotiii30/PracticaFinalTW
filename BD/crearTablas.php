<?php

// Datos de la conexión
/*$host = "localhost";
$usuario = "carlotadlavega2223";
$clave = "nQ69ZPy3";
$bbdd = "carlotadlavega2223";*/

$host = 'localhost';
$usuario = 'manuelvico01022223';
$clave = 'ABifAxaC';
$bbdd = 'manuelvico01022223';
$port = '8889';

// Conexión
$db = new mysqli($host, $usuario, $clave, $bbdd);
#$db = mysqli_connect($host,$usuario,$clave,$bbdd,$port);

if ($db) {
	echo "<p>Conexión con éxito</p>";

	// Borrar tabla anterior si existe
	$sql = "DROP TABLE IF EXISTS usuarios";

	// Ejecutar la consulta
	if ($db->query($sql) === TRUE) {
		   echo "Tabla anterior eliminada";
	   } else {
		   echo "Error al eliminar tabla anterior: " . $db->error;
		   exit;
	   }

	// Crear la tabla con la columna de foto
	$sql = "CREATE TABLE usuarios (
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
			   )";

	$sqll = "INSERT INTO usuarios (email, password)  VALUES ('manuel', '1234')";
	if ($db->query($sqll) === TRUE) {
		echo "Usuario insertado.<br>";
	} else {
		echo "Error al insertar al usuario: " . $db->error . "<br>";
	}

	/*$sql = "CREATE TABLE incidencias (
			   id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			   titulo VARCHAR(50) DEFAULT NULL,
			   descripcion text DEFAULT NULL,
			   fecha datetime DEFAULT NULL,
			   lugar VARCHAR(50) DEFAULT NULL,
			   keywords VARCHAR(30) DEFAULT NULL,
			   idusuario INT(10) DEFAULT NULL,
			   estado CHAR(30) DEFAULT NULL
			   )";*/

	// Ejemplo de datos para insertar en la tabla
	/*$incidencias = [
		[
			'titulo' => 'Incidencia 1',
			'descripcion' => 'Descripción de la incidencia 1',
			'fecha' => '2023-05-20 10:00:00',
			'lugar' => 'Lugar 1',
			'keywords' => 'keyword1, keyword2',
			'idusuario' => 1,
			'estado' => 'pendiente'
		],
		[
			'titulo' => 'Incidencia 2',
			'descripcion' => 'Descripción de la incidencia 2',
			'fecha' => '2023-05-21 14:30:00',
			'lugar' => 'Lugar 2',
			'keywords' => 'keyword3, keyword4',
			'idusuario' => 2,
			'estado' => 'tramitada'
		],
		[
			'titulo' => 'Incidencia 3',
			'descripcion' => 'Descripción de la incidencia 3',
			'fecha' => '2023-05-22 09:45:00',
			'lugar' => 'Lugar 3',
			'keywords' => 'keyword2, keyword5',
			'idusuario' => 1,
			'estado' => 'resuelta'
		]
	];*/
	
	// Generar y ejecutar las consultas de inserción
	/*foreach ($incidencias as $incidencia) {
		$sql = "INSERT INTO incidencias (titulo, descripcion, fecha, lugar, keywords, idusuario, estado) 
            VALUES ('{$incidencia['titulo']}', '{$incidencia['descripcion']}', '{$incidencia['fecha']}', 
                    '{$incidencia['lugar']}', '{$incidencia['keywords']}', {$incidencia['idusuario']}, 
                    '{$incidencia['estado']}')";

		if ($db->query($sql) === TRUE) {
			echo "Incidencia insertada exitosamente.<br>";
		} else {
			echo "Error al insertar la incidencia: " . $db->error . "<br>";
		}
	}*/

	// Tabla logs
	/*$sql = "CREATE TABLE logs (
		id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		fecha DATETIME,
		accion VARCHAR(255)
	  )";*/

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
