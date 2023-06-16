<?php
/**
 * Fichero para procesar el formulario de gestión de la base de datos.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

 require('../core/baseDatos.php');
 require('../core/copiaSeguridad.php');

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
   /* else if (isset($_POST["restaurar"])) {
        $_SESSION["restaurar"] = true;
        echo "<meta http-equiv='refresh' content='0;url=../gestionBD.php'>";
    } else if (isset($_POST["subir"])) {
        if ((sizeof($_FILES) == 0) || !array_key_exists("fichero", $_FILES)) {
            $_SESSION["mensaje"] = "No se ha podido subir el fichero";
            echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
        }
        else if (!is_uploaded_file($_FILES['fichero']['tmp_name'])) {
            $_SESSION["mensaje"] = "Fichero no subido. Código de error: " . $_FILES['fichero']['error'];
            echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
        }
        else {
            $_SESSION["mensaje"] = restaurar($db, $_FILES['fichero']['tmp_name']);
            echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
        }

        $_SESSION["restaurar"] = false;
    }*/

    // Opción 3: Borrar la BBDD (se reinicia)
    else if (isset($_POST["borrar"])) {
        $_SESSION["confirmar_borrar"] = true;
        echo "<meta http-equiv='refresh' content='0;url=../gestionBD.php'>";
    } else if (isset($_POST["confirmar_borrar"]) && isset($_POST["confirmar"]) && $_POST["confirmar"] === "si") {
        $_SESSION["confirmar_borrar"] = false;
        borrar($db, 0);
    }

    desconexion($db);
}


?>