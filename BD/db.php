<?php
require_once('credenciales.php');

// Conexión a la BBDD
// Devuelve un resource si hay éxito o una cadena con una descripción del error
function DB_conexion() {
  $db = new mysqli(host, admin, clave, bbdd);
  if (!$db)
    return "Error de conexión a la base de datos (".mysqli_connect_errno().") : ".mysqli_connect_error();

  // Establecer el conjunto de caracteres del cliente
  mysqli_set_charset($db,"utf8");

  return $db;
}

// Desconexión de la BBDD
function DB_desconexion($db) {
  mysqli_close($db);
}

?>