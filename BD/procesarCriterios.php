<?php
// Datos de la conexión
require_once('credenciales.php');

$db = new mysqli($host, $admin, $clave, $bbdd);

if ($db) {
    // Procesar los datos del formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos enviados del formulario
        $ordenar = $_POST['ordenar'];

        // Realizar la consulta en la base de datos según los criterios de búsqueda
        $sql = "SELECT * FROM tabla_datos ORDER BY ";
        if ($ordenar === "Antiguedad") {
            $sql .= "fecha_creacion";
        } elseif ($ordenar === "Mg") {
            $sql .= "me_gustas";
        } elseif ($ordenar === "NoMg") {
            $sql .= "no_me_gustas";
        }

        // Mostrar los resultados de la consulta
        if ($db->query($sql)->num_rows > 0) {
            while ($row = $db->query($sql)->fetch_assoc()) {
                // Mostrar los datos obtenidos según tus necesidades
                echo "ID: " . $row["id"] . "<br>";
                echo "Título: " . $row["titulo"] . "<br>";
                // ...
            }
        } else {
            echo "No se encontraron resultados.";
        }

        // Cerrar la conexión con la base de datos
        mysqli_close($db);
    }
}
?>