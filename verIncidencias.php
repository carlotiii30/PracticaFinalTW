<?php
require('vista/html/html.php'); // Maquetado de página

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavAdmin($mensajes[$idioma]["VerIncidencias"]);
htmlPagVerIncidencias();
htmlAside();
htmlEnd();
?>

