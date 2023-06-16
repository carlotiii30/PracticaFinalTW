<?php
/**
 * Fichero para mostrar la página del log.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

require('vista/html/html.php'); // Maquetado de página

// Cabecera y menu
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["Log"]);
htmlPagLog();
htmlAside();
htmlEnd();


?>