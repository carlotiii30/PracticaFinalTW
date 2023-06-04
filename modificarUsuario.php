<?php
require('./vista/html/html.php'); // Maquetado de página
require "BD/guardarCambios.php";

htmlStart('Modificar usuario');
htmlNavGeneral('');

if(isset($_POST['editar'])){
    $_SESSION['editando'] = $_POST['usuario'];
}
modificarUsuario($_SESSION['editando']);

htmlEnd();
?>