<?php
require('baseDatos.php'); // Conexión y desconexión

// Conexión
$db = conexion();

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
	 )";
*/

// Tabla comentarios.
/*
// Borrar tabla anterior si existe
$sql = "DROP TABLE IF EXISTS comentarios";

// Ejecutar la consulta
if ($db->query($sql) === TRUE) {
	echo "Tabla anterior eliminada";
} else {
	echo "Error al eliminar tabla anterior: " . $db->error;
	exit;
}

$sql = "CREATE TABLE comentarios (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT(10) DEFAULT NULL,
    comentario TEXT DEFAULT NULL,
    fecha DATETIME DEFAULT NULL
)";

$sql = "ALTER TABLE comentarios
ADD COLUMN idIncidencia INT(10) UNSIGNED,
ADD CONSTRAINT fk_comentarios_incidencias
FOREIGN KEY (idIncidencia) REFERENCES incidencias(id)
ON DELETE CASCADE";

CREATE TABLE valoraciones (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    valoracion INT(10) DEFAULT NULL,
    idIncidencia INT(10) UNSIGNED,
    idUsuario INT(10) UNSIGNED,
    CONSTRAINT fk_valoraciones_incidencias
    FOREIGN KEY (idIncidencia) REFERENCES incidencias(id)
    ON DELETE CASCADE,
    CONSTRAINT fk_valoraciones_usuarios
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id)
    ON DELETE CASCADE
);

// Ejecutar la consulta
if ($db->query($sql)) {
	echo "Relación entre tablas creada correctamente.";
} else {
	echo "Error al crear la relación entre tablas: " . $db->error;
}
/*
// Ejecutar la consulta
if ($db->query($sql) === TRUE) {
	echo "Tabla creada correctamente";
} else {
	echo "Error al crear tabla: " . $db->error;
	exit;
}

// tabla incidencias
/*$sql = "ALTER TABLE incidencias ADD COLUMN valoracionesPositivas INT DEFAULT 0";

if ($db->query($sql)) {
	echo "Tabla alterada correctamente.";
} else {
	echo "Error al alterar la tabla: " . $db->error;
}

$sql = "ALTER TABLE incidencias ADD COLUMN valoracionesNegativas INT DEFAULT 0";

if ($db->query($sql)) {
	echo "Tabla alterada correctamente.";
} else {
	echo "Error al alterar la tabla: " . $db->error;
}

*/

// TABLA IMAGENES
/*$sql = "CREATE TABLE fotos (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    idIncidencia INT(10) UNSIGNED,
	foto LONGBLOB
	)";
*/
//$sql = "ALTER TABLE incidencias DROP COLUMN idUsuario;";

/*$sql = "ALTER TABLE incidencias ADD COLUMN idUsuario INT(10) UNSIGNED DEFAULT NULL,
ADD FOREIGN KEY (idUsuario) REFERENCES usuarios (id)";
*/

//$sql = "ALTER TABLE comentarios DROP COLUMN idUsuario;";

/*$sql = "ALTER TABLE comentarios ADD COLUMN idUsuario INT(10) UNSIGNED DEFAULT NULL,
ADD FOREIGN KEY (idUsuario) REFERENCES usuarios (id)";
*/

//$sql = "ALTER TABLE incidencias DROP COLUMN valoracionesPositivas;";
$sql = "ALTER TABLE incidencias DROP COLUMN valoracionesNegativas;";

if ($db->query($sql) === TRUE) {
	echo "Tabla creada correctamente";
} else {
	echo "Error al crear tabla: " . $db->error;
	exit;
}

desconexion($db);

?>