<?php

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
    //return $salida;

    // Guardamos la copia en un archivo y ofrecemos la descarga.
    $f = fopen('db-backup-' . time() . '-' . (md5(implode(',', $tablas))) . '.sql', 'w+');
    fwrite($f, $salida);
    fclose($f);

    $archivo = 'db-backup-' . time() . '-' . (md5(implode(',', $tablas))) . '.sql';
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $archivo . '"');
    readfile($archivo);
    exit;

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
            $error .= mysqli_error($db);
    }
    mysqli_commit($db);
    mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=1');
    return $error;
}

// Borrar tablas
function borrar($db)
{
    $result = mysqli_query($db, 'SHOW TABLES');

    while ($row = mysqli_fetch_row($result)) {
        mysqli_query($db, 'DELETE FROM ' . $row[0]);
    }

    if (mysqli_commit($db)) {
        reiniciarNumeracionTablas($db);

        insertarLog("La base de datos se ha borrado", $db);
        
        // Crear usuario administrador
        $hash = password_hash('admin', PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion, rol, estado) 
                    VALUES ('admin', '', 'admin', '$hash', '', '', 'admin', 'activo')";

        if ($db->query($sql)) {
            insertarLog("Se ha creado el usuario admin", $db);
        }

    } else {
        insertarLog("Intento fallido de borrar la base de datos.", $db);
    }

    header("Location: index.php");
    __htmlLogout();
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