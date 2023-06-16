<?php
/**
 * Fichero para mostrar la página de nueva incidencia.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

require('vista/html/html.php'); // Maquetado de página

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["NuevaIncidencia"]);
htmlPagNuevaIncidencia();
htmlAside();
htmlEnd();
?>