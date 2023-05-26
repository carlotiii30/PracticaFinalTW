<?php
    require('vista/html/html.php');     // Maquetado de página

    // ************* Inicio de la página
    htmlStart('Sal y quéjate'); 
    htmlNavAdmin($mensajes[$idioma]["GestionBBDD"]);
    htmlPagInicio();
    htmlAside(false);
    htmlEnd();


    /*
        Hay tres opciones:
            -  Descargar copia de seguridad.
                -> Documento que borra y crea cada tabla.
                -> Inserta los logs.
                -> Los usuarios.
                -> Las incidencias.

            - Restaurar copia de seguridad (opcional).
            - Borrar la BBDD (se reinicia)
                -> Borra y crea cada tabla.
     */
?>