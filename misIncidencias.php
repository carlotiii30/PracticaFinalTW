<?php
require('vista/html/html.php');     // Maquetado de página

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["MisIncidencias"]);
htmlPagMisIncidencias();
htmlAside();
htmlEnd();
?>