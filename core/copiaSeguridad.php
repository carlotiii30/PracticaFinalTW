<?php
/**
 * Fichero para el procesamiento de gestion de la BD.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

if (!function_exists('insertarLog')) {

    /**
     * Función que inserta un registro en la tabla logs.
     * 
     * @param string $accion Acción que se va a registrar en la tabla logs.
     * @param mysqli $db Objeto mysqli que representa la conexión con la base de datos.
     */
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

/**
 * Función que genera una copia de seguridad de la base de datos.
 * 
 * @param mysqli $db Objeto mysqli que representa la conexión con la base de datos.
 * @return string Retorna un string con el contenido de la copia de seguridad.
 */
function backup($db)
{
    $tablas = array(); // Obtener listado de tablas
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


/**
 * Función que restaura una copia de seguridad de la base de datos.
 * 
 * @param mysqli $db Objeto mysqli que representa la conexión con la base de datos.
 * @param string $f Ruta del fichero que contiene la copia de seguridad.
 * 
 * @return string Retorna un string con los errores que se han producido durante la restauración.
 */
function restaurar($db, $f)
{
    mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=0');
    borrar($db, 1);
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

/**
 *  Función que borra todos los registros de todas las tablas de la base de datos.
 * 
 *  @param mysqli $db Objeto mysqli que representa la conexión con la base de datos.
 *  @param int $n Si $n es 0, se redirige a la página de inicio. Si $n es 1, no se redirige.
 * 
 *  @return void
 */
function borrar($db, $n)
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

    if ($n == 0) {
        header("Location: ../index.php");
        __htmlLogout();
    }
}

/**
 *  Función que reinicia la numeración de las tablas de la base de datos.
 * 
 *  @param mysqli $db Objeto mysqli que representa la conexión con la base de datos.
 */
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