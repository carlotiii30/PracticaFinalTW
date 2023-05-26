<?php
    require('vista/html/html.php');     // Maquetado de página
	global $mensajes;
    global $idioma;
    // ************* Inicio de la página
    htmlStart('Sal y quéjate'); 
    htmlNavAdmin($mensajes[$idioma]["MisIncidencias"]);
    htmlPagInicio();
    htmlAside(false);
    htmlEnd();
?>
