<?php
$db = conexion();

// Procesar los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['editar']) && !isset($_POST['logout']))) {
    // Sentencia por defecto si no se rellena el formulario, pero se envia, se muestran todas las incidencias
    
    $sql = "SELECT * FROM incidencias WHERE 1=1";
    
    if(isset($_POST['ordenar']) && $_POST['ordenar'] === "NoMg"){
        $sql = "SELECT i.*, COALESCE(SUM(v.valoracion = 1) - SUM(v.valoracion = 0), 0) AS diferencia_valoraciones
        FROM incidencias i
        LEFT JOIN valoraciones v ON i.id = v.idIncidencia
        WHERE 1=1";
    }else if(isset($_POST['ordenar']) && $_POST['ordenar'] === "Mg"){
        $sql = "SELECT i.*, SUM(v.valoracion = 1) AS valoraciones_positivas 
        FROM incidencias i
        LEFT JOIN valoraciones v ON i.id = v.idIncidencia
        WHERE 1=1";
    }

    if(isset($_POST['pagina']) && $_POST['pagina'] == "misIncidencias"){
        $idUsuario = $_SESSION['idUsuario'];
        if(isset($_POST['ordenar']) && ($_POST['ordenar'] === "Mg" || $_POST['ordenar'] === "NoMg"))
            $sql .= " AND i.idUsuario = ?";
        else
            $sql .= " AND idUsuario = ?";
    }
    
    // FIltra según el estado de las incidencias
    if(isset($_POST['estado']) && !empty($_POST['estado'])){
        $estados = $_POST['estado'];
        $sqlEstados = "";
        foreach ($estados as $estado){
            $sqlEstados .= " OR estado = ?";
        }
        //La función ltrim elimina el primer OR que encuentra en sqlEstados
        $sql .= " AND (" . ltrim($sqlEstados, " OR") . ")";
    }

    // Filtra según el lugar
    if(isset($_POST['lugar']) && !empty($_POST['lugar'])){
        $lugar = $_POST['lugar'];

        $sql .= " AND lugar = ?";
    }

    // Filtra si el texto esta contenido en la descripción
    if(isset($_POST['texto']) && !empty($_POST['texto'])){
        $texto = $_POST['texto'];
        
        $sql .= " AND (descripcion LIKE ? OR titulo LIKE ? OR keywords LIKE ?)";
    }

    if(isset($_POST['ordenar'])){
        $ordenar = $_POST['ordenar'];

        // Ordenar según lo seleccionado
        if ($ordenar === "Antiguedad") {
            $sql .= " ORDER BY fecha DESC";
        } elseif ($ordenar === "Mg") {
            $sql .= " GROUP BY i.id ORDER BY valoraciones_positivas DESC";
        } elseif ($ordenar === "NoMg") {
            $sql .= " GROUP BY i.id ORDER BY diferencia_valoraciones DESC;";
        }
    }

    // Preparar la consulta con sentencias preparadas
    $stmt = $db->prepare($sql);

    // Asignar los parámetros de las sentencias preparadas
    if(isset($_POST['pagina']) && $_POST['pagina'] == "misIncidencias"){
        $idUsuario = $_SESSION['idUsuario'];
        if(isset($_POST['ordenar']) && ($_POST['ordenar'] === "Mg" || $_POST['ordenar'] === "NoMg"))
            $stmt->bind_param("s", $idUsuario);
        else
            $stmt->bind_param("s", $idUsuario);
    }

    if(isset($_POST['estado']) && !empty($_POST['estado'])){
        $estados = $_POST['estado'];
        foreach ($estados as $estado){
            $stmt->bind_param("s", $estado);
        }
    }

    if(isset($_POST['lugar']) && !empty($_POST['lugar'])){
        $stmt->bind_param("s", $lugar);
    }

    if(isset($_POST['texto']) && !empty($_POST['texto'])){
        $texto = "%$texto%";
        $stmt->bind_param("sss", $texto, $texto, $texto);
    }

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->get_result();

    if(isset($result)){
        $incidencias = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $incidencias[] = $row;
            }
        }
    }

    // Cerrar la declaración y la conexión con la base de datos
    $stmt->close();
    desconexion($db);
}

?>
