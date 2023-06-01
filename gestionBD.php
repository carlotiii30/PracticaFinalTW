<?php
require('vista/html/html.php'); // Maquetado de página
require('BD/copiaSeguridad.php'); // Backup

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["GestionBBDD"]);
htmlPagGestionBD();
htmlAside();
htmlEnd();
?>