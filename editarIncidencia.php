<?php
/**
 * Fichero para mostrar la página de editar incidencia.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

require('vista/html/html.php'); // Maquetado de página

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["VerIncidencias"]);

if(isset($_POST['editarInc'])){
    $_SESSION['editandoInc'] = $_POST['editarInc'];
}

if(isset($_SESSION['editandoInc'])){
    htmlPagEditarIncidencia($_SESSION['editandoInc']);
}else{
    header('Location: index.php');
    exit;
}
htmlAside();
htmlEnd();
?>