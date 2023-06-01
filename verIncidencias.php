<?php
require('vista/html/html.php'); // Maquetado de página
include "BD/procesarCriterios.php"; //Procesado del formulario

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["VerIncidencias"]);
htmlPagVerIncidencias();
if(isset($incidencias)){
    mostrarIncidencias($incidencias);
}else{
    echo '</div>';
}
htmlAside();
htmlEnd();
?>

