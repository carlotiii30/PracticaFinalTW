<?php
    require('vista/html/html.php');     // Maquetado de página
    require('BD/copiaSeguridad.php');  // Backup

    // ************* Inicio de la página
    htmlStart('Sal y quéjate'); 
    htmlNavGeneral($mensajes[$idioma]["GestionBBDD"]);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $db = conexion();
        // Opción 1: Descargar copia de seguridad
        if (isset($_POST["descargar"])) {
            backup($db);
        }

        // Opción 2: Restaurar copia de seguridad
        else if (isset($_POST["restaurar"])) {
            //restaurar($db, );
        }

        // Opción 3: Borrar la BBDD (se reinicia)
        else if (isset($_POST["borrar"])) {
            borrar($db);
        }

        desconexion($db);
    }
?>
<div class ="gestion">
    <form method="post" action="">
        <!-- Opción 1: Descargar copia de seguridad -->
        <input type="submit" name="descargar" value="Descargar copia de seguridad">

        <!-- Opción 2: Restaurar copia de seguridad -->
        <input type="submit" name="restaurar" value="Restaurar copia de seguridad">

        <!-- Opción 3: Borrar la BBDD (se reinicia) -->
        <input type="submit" name="borrar" value="Borrar la base de datos">
    </form>
</div>

<?php
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