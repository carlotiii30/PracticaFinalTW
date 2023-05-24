<?php
// Datos de la conexión
require_once('credenciales.php');

$db = new mysqli(host, admin, clave, bbdd);

if ($db) {
    // Procesar los datos del formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos enviados del formulario
        $ordenar = $_POST['ordenar'];

        // Realizar la consulta en la base de datos según los criterios de búsqueda
        $sql = "SELECT * FROM incidencias ORDER BY ";
        if ($ordenar === "Antiguedad") {
            $sql .= "fecha DESC";
        } elseif ($ordenar === "Mg") {
            $sql .= "me_gustas";
        } elseif ($ordenar === "NoMg") {
            $sql .= "no_me_gustas";
        }

        // Mostrar los resultados de la consulta
        $result = $db->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Mostrar los datos obtenidos según tus necesidades
                echo "ID: " . $row["id"] . "<br>";
                echo "Título: " . $row["titulo"] . "<br>";
                echo "Descripción: " . $row["descripcion"] . "<br>";
                echo "Fecha: " . $row["fecha"] . "<br>";
                echo "Lugar: " . $row["lugar"] . "<br>";
                echo "Palabras clave: " . $row["keywords"] . "<br>";
                echo "ID Usuario: " . $row["idusuario"] . "<br>";
                echo "Estado: " . $row["estado"] . "<br>";
                echo "<br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }

        // Liberar el resultado y cerrar la conexión con la base de datos
        $result->free();
        mysqli_close($db);
    }
}
?>