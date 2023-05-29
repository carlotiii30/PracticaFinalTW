<?php
require('baseDatos.php'); // Conexión y desconexión

$db = conexion();

// Procesar los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados del formulario
    if(isset($_POST['ordenar'])){
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
    }

    //include("../verIncidencias.php");
    //$resultadosHTML = '';
    if(isset($result)){
        $incidencias = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $incidencias[] = $row;
                /*// Mostrar los datos obtenidos según tus necesidades
                $resultadosHTML .= "ID: " . $row["id"] . "<br>";
                $resultadosHTML .= "Título: " . $row["titulo"] . "<br>";
                $resultadosHTML .= "Descripción: " . $row["descripcion"] . "<br>";
                $resultadosHTML .= "Fecha: " . $row["fecha"] . "<br>";
                $resultadosHTML .= "Lugar: " . $row["lugar"] . "<br>";
                $resultadosHTML .= "Palabras clave: " . $row["keywords"] . "<br>";
                $resultadosHTML .= "ID Usuario: " . $row["idusuario"] . "<br>";
                $resultadosHTML .= "Estado: " . $row["estado"] . "<br>";
                $resultadosHTML .= "<br>";*/
            }
        } else {
            //$resultadosHTML .= "No se encontraron resultados.";
        }
        // Liberar el resultado
        $result->free();
    }

    // cerrar la conexión con la base de datos
    desconexion($db);
    //echo $resultadosHTML;
}

?>