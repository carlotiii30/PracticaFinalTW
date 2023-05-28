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


?>