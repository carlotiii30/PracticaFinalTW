<?php

// - - - Conexión y desconexión - - - -

require_once('credenciales.php');

// Conexión a la BBDD
function conexion()
{
    $db = mysqli_connect(host, admin, clave, bbdd);
    if (!$db)
        return "Error de conexión a la base de datos (" . mysqli_connect_errno() . ") : " . mysqli_connect_error();

    // Establecer el conjunto de caracteres del cliente
    mysqli_set_charset($db, "utf8");

    return $db;
}

// Desconexión de la BBDD
function desconexion($db)
{
    mysqli_close($db);
}

?>