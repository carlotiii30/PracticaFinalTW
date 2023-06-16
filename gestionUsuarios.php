<?php
/**
 * Fichero para mostrar la página de gestión de usuarios.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

require('vista/html/html.php'); // Maquetado de página
include "BD/procesarCriterios.php"; //Procesado del formulario

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["GestionUsuarios"]);
htmlPagGestionUsuarios();
htmlAside();
htmlEnd();
?>