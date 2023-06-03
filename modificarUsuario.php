<?php
require('./vista/html/html.php'); // Maquetado de página
require "BD/guardarCambios.php";

htmlStart('Modificar usuario');
htmlNavGeneral('');
htmlEnd();
modificarUsuario($_SESSION['idUsuario']);
?>