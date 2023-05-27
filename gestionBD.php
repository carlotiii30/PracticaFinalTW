<?php
    require('vista/html/html.php');     // Maquetado de página

    // ************* Inicio de la página
    htmlStart('Sal y quéjate'); 
    htmlNavAdmin($mensajes[$idioma]["GestionBBDD"]);
    htmlPagInicio();
    htmlAside();
    htmlEnd();


    /*
        Hay tres opciones:
            -  Descargar copia de seguridad.
                -> Documento que borra y crea cada tabla.
                -> Inserta los logs.
                -> Los usuarios.
                -> Las incidencias.

            - Restaurar copia de seguridad (opcional).
                -> Página 33 de TW33.

            - Borrar la BBDD (se reinicia)
                -> Borra y crea cada tabla.
     */
?>