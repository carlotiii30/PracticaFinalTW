<?php
require('vista/html/html.php');     // Maquetado de página

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["MisIncidencias"]);

$db = conexion();

$id = $_SESSION['idUsuario'];
$sql = "SELECT * FROM incidencias WHERE idusuario = $id ORDER BY fecha DESC";
$datos = $db->query($sql);

desconexion($db);

htmlPagMisIncidencias($datos);
htmlAside();
htmlEnd();
?>