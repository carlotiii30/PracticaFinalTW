<?php
require('vista/html/html.php'); // Maquetado de página

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["VerIncidencias"]);
htmlPagVerIncidencias();
htmlAside();
htmlEnd();
?>

