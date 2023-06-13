<?php

if (!function_exists('insertarLog')) {
    function insertarLog($accion, $db)
    {
        $accion = "INFO: " . $accion; // Concatenar "INFO: " al inicio de $accion
        $sql = "INSERT INTO logs (fecha, accion)
                    VALUES (NOW(), ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $accion); // Pasar el valor de $accion concatenado
        $stmt->execute();
        $stmt->close();
    }
}

// Copia de seguridad de la base de datos
function backup($db)
{
    // Obtener listado de tablas
    $tablas = array();
    $result = mysqli_query($db, 'SHOW TABLES');

    while ($row = mysqli_fetch_row($result))
        $tablas[] = $row[0];

    // Salvar cada tabla
    $salida = '';
    foreach ($tablas as $tab) {
        $result = mysqli_query($db, 'SELECT * FROM ' . $tab);
        $num = mysqli_num_fields($result);

        $salida .= 'DROP TABLE IF EXISTS ' . $tab . ';';
        $row2 = mysqli_fetch_row(mysqli_query($db, 'SHOW CREATE TABLE ' . $tab));
        $salida .= "\n\n" . $row2[1] . ";\n\n";

        while ($row = mysqli_fetch_row($result)) {
            $salida .= 'INSERT INTO ' . $tab . ' VALUES(';
            for ($j = 0; $j < $num; $j++) {
                if (!is_null($row[$j])) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                    if (isset($row[$j]))
                        $salida .= '"' . $row[$j] . '"';
                    else
                        $salida .= '""';
                } else
                    $salida .= 'NULL';
                if ($j < ($num - 1))
                    $salida .= ',';
            }
            $salida .= ");\n";
        }
        $salida .= "\n\n\n";
    }
    return $salida;
}

// Restaurar base de datos
function restaurar($db, $f)
{
    mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=0');
    borrar($db);
    $error = [];
    $sql = file_get_contents($f);
    $queries = explode(';', $sql);
    foreach ($queries as $q) {
        $q = trim($q);
        if ($q != '' and !mysqli_query($db, $q))
            $error[] = mysqli_error($db);
    }
    mysqli_commit($db);
    mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=1');

    insertarLog("La base de datos se ha restaurado desde un fichero", $db);

    return implode("\n", $error);
}

// Borrar tablas
function borrar($db)
{
    $result = mysqli_query($db, 'SHOW TABLES');

    while ($row = mysqli_fetch_row($result)) {
        $table = mysqli_real_escape_string($db, $row[0]);
        mysqli_query($db, "DELETE FROM $table");
    }

    if (mysqli_commit($db)) {
        reiniciarNumeracionTablas($db);

        insertarLog("La base de datos se ha borrado", $db);

        // Crear usuario administrador
        $hash = password_hash('admin', PASSWORD_BCRYPT);
        $nombre = mysqli_real_escape_string($db, 'admin');
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion, rol, estado) 
                    VALUES ('$nombre', '', 'admin', '$hash', '', '', 'admin', 'activo')";

        if ($db->query($sql)) {
            insertarLog("Se ha creado el usuario admin", $db);
        }

    } else {
        insertarLog("Intento fallido de borrar la base de datos.", $db);
    }

    /*if (!isset($_SESSION["restaurar"]) || (isset($_SESSION["restaurar"]) && !$_SESSION["restaurar"])) {
        header("Location: ../index.php");
        __htmlLogout();
    }*/
}


// Reiniciar numeracion
function reiniciarNumeracionTablas($db)
{
    $tables = array();
    $result = mysqli_query($db, 'SHOW TABLES');

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    foreach ($tables as $table) {
        mysqli_query($db, "ALTER TABLE $table AUTO_INCREMENT = 1");
    }
}

?>