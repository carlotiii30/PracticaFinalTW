<?php
/**
 * Fichero para mostrar la página de editar un usuario.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

require('./vista/html/html.php'); // Maquetado de página
require "BD/guardarCambios.php";

htmlStart('Modificar usuario');
htmlNavGeneral('');

if(isset($_POST['editar'])){
    $_SESSION['editando'] = $_POST['usuario'];
}

if(isset($_SESSION['editando'])){
    modificarUsuario($_SESSION['editando']);
}else{
    header('Location: index.php');
    exit;
}

htmlEnd();
?>