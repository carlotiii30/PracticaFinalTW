<?php

$db = conexion();

// Procesar los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['editar']) && !isset($_POST['logout']))) {
    // Sentencia por defecto si no se rellena el formulario, pero se envia, se muestran todas las incidencias
    $sql = "SELECT * FROM incidencias WHERE 1=1";
    
    if(isset($_POST['ordenar']) && $_POST['ordenar'] === "NoMg"){
        $sql = "SELECT *, (valoracionesPositivas - valoracionesNegativas) AS diferencia FROM incidencias WHERE 1=1";
    }
    
    // FIltra según el estado de las incidencias
    if(isset($_POST['estado']) && !empty($_POST['estado'])){
        $estados = $_POST['estado'];
        $sqlEstados = "";
        foreach ($estados as $estado){
            $sqlEstados .= " OR estado = '$estado'";
        }
        //La función ltrim elimina el primer OR que encuentra en sqlEstados
        $sql .= " AND (" . ltrim($sqlEstados, " OR") . ")";
    }

    // Filtra según el lugar
    if(isset($_POST['lugar']) && !empty($_POST['lugar'])){
        $lugar = mysqli_real_escape_string($db, $_POST['lugar']);

        $sql .= " AND lugar = '$lugar'";
    }

    // Filtra si el texto esta contenido en la descripción
    if(isset($_POST['texto']) && !empty($_POST['texto'])){
        $texto = mysqli_real_escape_string($db, $_POST['texto']);

        $sql .= " AND descripcion LIKE '%$texto%'";
    }

    if(isset($_POST['ordenar'])){
        $ordenar = $_POST['ordenar'];

        // Ordenar según lo seleccionado
        $sql .= " ORDER BY";
        if ($ordenar === "Antiguedad") {
            $sql .= " fecha DESC";
        } elseif ($ordenar === "Mg") {
            $sql .= " valoracionesPositivas DESC";
        } elseif ($ordenar === "NoMg") {
            $sql .= " diferencia DESC";
        }
    }

    // Mostrar los resultados de la consulta
    $result = $db->query($sql);

    if(isset($result)){
        $incidencias = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $incidencias[] = $row;
            }
        }
        // Liberar el resultado
        $result->free();
    }

    // cerrar la conexión con la base de datos
    desconexion($db);
}

?>