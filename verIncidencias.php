<?php
require('vista/html/html.php'); // Maquetado de página
include "BD/procesarCriterios.php"; //Procesado del formulario
require("funcion.php");

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["VerIncidencias"]);
htmlPagVerIncidencias();
if(isset($incidencias)){
    MostrarIncidencias($incidencias);
}else{
    echo '</div>';
}
htmlAside();
htmlEnd();
?>

