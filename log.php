<?php
require('vista/html/html.php'); // Maquetado de página

// Cabecera y menu
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["Log"]);

// Código de log
$db = conexion();

$sql = "SELECT * FROM logs ORDER BY fecha DESC";
$datos = $db->query($sql);

desconexion($db);

// Visualización de log, aside y fin
htmlPagLog($datos);
htmlAside();
htmlEnd();


?>