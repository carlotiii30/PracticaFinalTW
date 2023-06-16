<?php
/**
 * Fichero para mostrar la página de registrar un usuario.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

include('vista/html/html.php'); // Maquetado de página
require "BD/procesarRegistro.php";

$erroresRegistro = array();

htmlStart('Página de registro');
htmlNavGeneral('');

if (isset($_POST["enviar"]) || isset($_POST["confirmar"]))
	registrarUsuario();

if (isset($_POST["enviarFoto"])) {
	if ($_SESSION['nuevoUsuario'] != 0)
		agregarFoto($_SESSION['nuevoUsuario']);
}

htmlPagRegistrarUsuario($erroresRegistro);
htmlEnd();
?>