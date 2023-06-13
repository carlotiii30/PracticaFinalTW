<?php
require("baseDatos.php");
require("copiaSeguridad.php");

session_start();

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
        if (isset($_FILES["archivo"])) {
            // Restaurar la copia de seguridad usando $_FILES["archivo"]
        }
    }

    // Opción 3: Borrar la BBDD (se reinicia)
    else if (isset($_POST["borrar"])) {
        $_SESSION["confirmar_borrar"] = true;
        echo "<meta http-equiv='refresh' content='0;url=../gestionBD.php'>";
    } else if (isset($_POST["confirmar_borrar"]) && isset($_POST["confirmar"]) && $_POST["confirmar"] === "si") {
        $_SESSION["confirmar_borrar"] = false;
        borrar($db);
    }

    desconexion($db);
}


?>