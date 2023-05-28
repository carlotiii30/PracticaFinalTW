<?php
    require('vista/html/html.php');     // Maquetado de página
	global $mensajes;
    global $idioma;
    // ************* Inicio de la página
    htmlStart('Sal y quéjate'); 
    htmlNavGeneral($mensajes[$idioma]["MisIncidencias"]);
    htmlPagInicio();
    htmlAside();
    htmlEnd();

    # Esta es igual que ver incidencias, solo que hay que añadir a la consulta WHERE idusuario = usuario activo
?>
