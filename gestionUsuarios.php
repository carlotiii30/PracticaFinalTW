<?php
require('vista/html/html.php'); // Maquetado de página
include "BD/procesarCriterios.php"; //Procesado del formulario

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["GestionUsuarios"]);

// Recuperar usuarios de la base de datos.
$db = conexion();

$sql = "SELECT * FROM usuarios";
$datos = $db->query($sql);

desconexion($db);

htmlPagGestionUsuarios($datos);
htmlAside();
htmlEnd();
?>