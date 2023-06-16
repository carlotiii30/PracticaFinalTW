<?php
/**
 * Fichero con las funciones relacionadas con la conexión y desconexión de la base de datos.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

require_once('credenciales.php');


/**
 * Establece una conexión con la base de datos.
 *
 * @return mysqli|string Retorna un objeto mysqli si la conexión se establece correctamente.
 *                       Si hay un error de conexión, retorna un mensaje de error en formato string.
 */
function conexion()
{
    $db = mysqli_connect(host, admin, clave, bbdd);
    if (!$db)
        return "Error de conexión a la base de datos (" . mysqli_connect_errno() . ") : " . mysqli_connect_error();

    // Establecer el conjunto de caracteres del cliente
    mysqli_set_charset($db, "utf8");

    return $db;
}

/**
 * Cierra la conexión con la base de datos.
 *
 * @param mysqli $db Objeto mysqli que representa la conexión con la base de datos.
 */
function desconexion($db)
{
    mysqli_close($db);
}

?>