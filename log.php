<?php
require('vista/html/html.php'); // Maquetado de página
require('BD/baseDatos.php'); // Conexión y desconexión

// Cabecera y menu
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["Log"]);

// Código de log
$db = conexion();

$sql = "SELECT * FROM logs ORDER BY fecha DESC";
$datos = $db->query($sql);

desconexion($db);

// Visualización de log, aside y fin
htmlPagLog($datos);
htmlAside();
htmlEnd();

/*
    Sale directamente la tabla de logs:
        (fecha) (accion)
    
    Para insertar logs: (la tabla ya está creada)
        function insertarLog($accion) {
            $sql = "INSERT INTO logs (fecha, accion)
                    VALUES (NOW(), ?)";
            $stmt = $db -> prepare($sql);
            $stmt->bind_param("s", $accion);
            $stmt->execute();
            $stmt->close();
        }
*/

?>