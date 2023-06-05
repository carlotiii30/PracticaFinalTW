<?php
require('vista/html/html.php'); // Maquetado de página
include "BD/procesarCriterios.php"; //Procesado del formulario

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["VerIncidencias"]);

if(isset($_POST['editarInc'])){
    $_SESSION['editandoInc'] = $_POST['editarInc'];
}
htmlPagEditarIncidencia($_SESSION['editandoInc']);
htmlAside();
htmlEnd();
?>