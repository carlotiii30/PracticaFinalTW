<?php
/**
 * Fichero con el código inicial de todas las páginas.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

include "core/baseDatos.php";
include "funcion.php";
include "BD/procesarIncidencia.php";

// - - - - Guardamos la URL de la página actual - - - -
$pagina_actual = basename($_SERVER['PHP_SELF']);

// - - - Cargamos los mensajes - - -
$mensajes = json_decode(file_get_contents('./vista/traducciones/traducciones.json'), true);

// - - - Traducciones para nueva incidencia - - -
$mensajesIncidencias = json_decode(file_get_contents('./vista/traducciones/formularioIncidencia.json'), true);

// - - - Traducciones para ver incidencias - - - 
$mensajesCriterios = json_decode(file_get_contents('./vista/traducciones/formularioCriterios.json'), true);

// - - - Traducciones para gestion de usuarios - - -
$mensajesRegistro = json_decode(file_get_contents('./vista/traducciones/formularioRegistro.json'), true);

// - - - Traducciones para backup - - -
$mensajesBackup = json_decode(file_get_contents('./vista/traducciones/backup.json'), true);

$erroresIncidencia = array();

// - - - - URLs - - - - 
$enlaces = array(
    "verIncidencias.php" => "VerIncidencias",
    "nuevaIncidencia.php" => "NuevaIncidencia",
    "misIncidencias.php" => "MisIncidencias",
    "gestionUsuarios.php" => "GestionUsuarios",
    "log.php" => "Log",
    "gestionBD.php" => "GestionBBDD"
);

// - - - Comprobamos si el formulario se ha enviado - - -
if (isset($_GET) and !empty($_GET)) {

    if (isset($_GET["idioma"]) and isset($_GET["aplicar"])) {
        $idioma = $_GET["idioma"];
        setcookie("idioma", $idioma);
    } else {
        $idioma = $_COOKIE["idioma"];
    }

} else {
    // Si existe una cookie, la recuperamos
    if (isset($_COOKIE["idioma"]) && !empty($_COOKIE["idioma"])) {
        $idioma = $_COOKIE["idioma"];
    } else {
        // Si no, inicializamos la variable idioma
        $idioma = "es";
    }
}

// - - - Funcion que comprueba si está seleccionado para marcarlo - - - -
function seleccionado($n, $v)
{
    if (isset($_GET[$n]) and ($_GET[$n] == $v))
        return 'selected';

    else if (isset($_COOKIE[$n]) && ($_COOKIE[$n] == $v)) {
        return 'selected';
    }
}


?>