<?php
require('vista/html/html.php'); // Maquetado de página

// Cabecera y menu
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["Log"]);
htmlPagLog();
htmlAside();
htmlEnd();


?>