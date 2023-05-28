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
    $query = "INSERT INTO $tabla (foto) VALUES(?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $image);
    $stmt->execute();
}

//Obtener foto no consigo que funcione


?>