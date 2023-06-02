<?php

// Función para insertar una fila a la tabla de log 
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

// Función para subir la foto de un usuario o de una incidencia indicar a que tabla insertar si usuarios o fotos
//La función sirve, para que funcione tiene que haber enviado un formulario donde un input sea:
// <input type="file" name="images"> y poner <form method="POST" action="esto da igual" enctype="multipart/form-data">
function subirFoto($tabla, $db){
    $image = file_get_contents($_FILES['images']['tmp_name']);
    $id = $_SESSION['idUsuario'];
    $query = "UPDATE $tabla SET foto = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('si', $image, $id);
    $stmt->execute();
}

// Descargar foto de un usuario
function descargarFoto($tabla, $idUsuario, $db)
{
    $foto = null;
    $query = "SELECT foto FROM $tabla WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $stmt->bind_result($foto);

    $imageData = '';
    if ($stmt->fetch()) {
        $fotoData = base64_encode($foto);
        $src = 'data:image/jpeg;base64,' . $fotoData;
        $imageData = "<img src='$src' alt='Foto'>";
    } else {
        $imageData = "Foto no encontrada.";
    }

    $stmt->close();

    echo $imageData;
}


// Función para las valoraciones
function valoracion($incidencia, $accion)
{
    //¿Cómo se supone que vota el visitante?
    $db = conexion();
    $idIncidencia = $incidencia;
    $valoracion = null;
    $idUsuario = $_SESSION['idUsuario'];
    $nombre = $_SESSION['nombreUsuario'];
    if($accion == "sumar"){
        $valoracion = 1;
        $mensaje = "El usuario $nombre ha valorado positivamente la incidencia $idIncidencia.";
        $confirmacion = "¡Wow! Parece que está de acuerdo con una incidencia. ¡Qué de apoyo hay en la comunidad!";
    }else if ($accion == "restar"){
        $valoracion = 0;
        $mensaje = "El usuario $nombre ha valorado negativamente la incidencia $idIncidencia.";
        $confirmacion = "Oh oh... ¿Hay algún problema con esa incidencia valorada negativamente? Deje un comentario para expresar su opinión.";
    }

    $sql = "INSERT INTO valoraciones (valoracion, idIncidencia, idUsuario)
    VALUES ($valoracion, $idIncidencia, $idUsuario);";

    $resultado = $db->query($sql);

    if($resultado) {
        $_SESSION['mensaje'] = $confirmacion;
        insertarLog($mensaje, $db);
        header("Location: index.php");
        exit;
    }
}

// Método para obtener el nombre de usuario a partir de la id de la tabla de incidencias.

function obtenerNombreUsuario($idUsuario)
{
    $db = conexion();

    $sql = "SELECT nombre, apellidos FROM usuarios WHERE id = $idUsuario";
    $result = $db->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombreUsuario = $row['nombre'];
        $apellidos = $row['apellidos'];
    } else {
        $nombreUsuario = 'Usuario no encontrado';
        $apellidos = '';
    }

    desconexion($db);

    return $nombreUsuario . ' ' . $apellidos;
}

function obtenerValoraciones($idIncidencia){
    $db = conexion();
    $sql = "SELECT SUM(CASE WHEN valoracion = 1 THEN 1 ELSE 0 END) AS positivas,
                    SUM(CASE WHEN valoracion = 0 THEN 1 ELSE 0 END) AS negativas
            FROM valoraciones
            WHERE idIncidencia = $idIncidencia
            GROUP BY idIncidencia";

    $resultado = $db->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $valoraciones = $resultado->fetch_assoc();
    } else {
        $valoraciones['positivas'] = 0;
        $valoraciones['negativas'] = 0;
    }

    desconexion($db);

    return $valoraciones;

}

function puedeValorar($idIncidencia){
    $puedeVotar = false;

    if(isset($_SESSION["autenticado"]) && isset($_SESSION["idUsuario"])){
        $idUsuario =  $_SESSION["idUsuario"];

        $db = conexion();

        $sql = "SELECT * FROM valoraciones
        WHERE idUsuario = $idUsuario
        AND idIncidencia = $idIncidencia";

        $resultado = $db->query($sql);
        if ($resultado->num_rows == 0) {
            $puedeVotar = true;
        }

        desconexion($db);
    }else{
        $nombreCookie = "VotacionIncidencia_". $idIncidencia;
        if(!isset($_COOKIE[$nombreCookie])){
            setcookie($nombreCookie, "Ha votado la incidencia", 0);
            $puedeVotar = true;
        }
    }

    return $puedeVotar;
}
?>