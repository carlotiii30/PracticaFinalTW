<?php
    require('vista/html/html.php');     // Maquetado de página

    // ************* Inicio de la página
    htmlStart('Sal y quéjate'); 
    htmlNavGeneral($mensajes[$idioma]["GestionUsuarios"]);
    htmlPagInicio();
    htmlAside();
    htmlEnd();

    /* Hay dos opciones:
            - Listado (para mostrar todos los usuarios)
                -> Usuario (Nombre y apellidos)
                -> Email
                -> Dirección
                -> Teléfono
                -> Rol
                -> Estado
                -> Foto
            - Añadir nuevo
                -> Formulario con todas las opciones
                -> Incluye rol y estado.

        Por defecto la opción seleccionada es listado. Es decir,
        se muestra el listado a menos que le des a a añadir nuevo.
    */
?>