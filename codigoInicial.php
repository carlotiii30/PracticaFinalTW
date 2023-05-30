<?php

// - - - - Guardamos la URL de la página actual - - - -
$pagina_actual = basename($_SERVER['PHP_SELF']);

// - - - Cargamos los mensajes - - -
$mensajes = json_decode(file_get_contents('./vista/traducciones/traducciones.json'), true);

// - - - Traducciones para nueva incidencia - - -
$mensajesIncidencias = json_decode(file_get_contents('./vista/traducciones/formularioIncidencia.json'), true);

// - - - Traducciones para ver incidencias - - - 
$mensajesCriterios = json_decode(file_get_contents('./vista/traducciones/formularioCriterios.json'), true);

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
    // Inicializamos la variable idioma
    $idioma = "es";
}

// - - - Funcion que comprueba si está seleccionado para marcarlo - - - -
function seleccionado($n, $v)
{
    if (isset($_GET[$n]) and ($_GET[$n] == $v))
        return 'selected';
    
}


?>
