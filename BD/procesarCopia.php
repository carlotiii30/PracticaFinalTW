<?php
require("baseDatos.php");
require("copiaSeguridad.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = conexion();

    // Opción 1: Descargar copia de seguridad
    if (isset($_POST["descargar"])) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="db_backup.sql"');
        echo backup($db);
    }

    // Opción 2: Restaurar copia de seguridad
    else if (isset($_POST["restaurar"])) {
        //restaurar($db, );
    }

    // Opción 3: Borrar la BBDD (se reinicia)
    else if (isset($_POST["confirmar_borrar"]) && isset($_POST["confirmar"]) && $_POST["confirmar"] === "si") {
        borrar($db);
    }

    desconexion($db);
}

?>