<?php
/**
 * Fichero para mostrar la página de gestión de Base de Datos.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

require('vista/html/html.php'); // Maquetado de página
require('core/copiaSeguridad.php'); // Backup

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["GestionBBDD"]);
htmlPagGestionBD();
htmlAside();
htmlEnd();
?>