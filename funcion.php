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
function descargarFoto($tabla, $db) {
    $foto = null;
    $id = $_SESSION['idUsuario'];
    $query = "SELECT foto FROM $tabla WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($foto);
    
    if ($stmt->fetch()) {
        $fotoData = base64_encode($foto);
        $src = 'data:image/jpeg;base64,' . $fotoData;
        echo "<img src='$src' alt='Foto'>";
    } else {
        echo "Foto no encontrada.";
    }
    
    $stmt->close();
}


// Función para las valoraciones
function valoracion($incidencia, $accion) {
    $db = conexion();
    $id = $incidencia;

    // Obtener el valor actual de valoraciones positivas y negativas
    $sql = "SELECT valoracionesPositivas, valoracionesNegativas FROM incidencias WHERE id = $id";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    $positiva = $row["valoracionesPositivas"];
    $negativa = $row["valoracionesNegativas"];

    if ($accion == "sumar") {
        $positiva++;
        $sql = "UPDATE incidencias SET valoracionesPositivas = $positiva WHERE id = $id";
        insertarLog("¡Parece que a alguien le ha gustado la incidencia con id $id!", $db);
    } elseif ($accion == "restar") {
        $negativa++;
        $sql = "UPDATE incidencias SET valoracionesNegativas = $negativa WHERE id = $id";
        insertarLog("Vaya alguien no está de acuerdo con la incidencia con id $id!", $db);
    }

    $db->query($sql);
}

// Método para obtener el nombre de usuario a partir de la id de la tabla de incidencias.
function obtenerNombreUsuario($idUsuario)
{
  $db = conexion();

  $sql = "SELECT nombre FROM usuarios WHERE id = $idUsuario";
  $result = $db->query($sql);

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombreUsuario = $row['nombre'];
  } else {
    $nombreUsuario = 'Usuario no encontrado';
  }

  desconexion($db);

  return $nombreUsuario;
}

?>