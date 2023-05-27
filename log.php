<?php
require('vista/html/html.php'); // Maquetado de página
require('BD/baseDatos.php'); // Conexión y desconexión

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavAdmin($mensajes[$idioma]["Log"]);
htmlAside(false);
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

function verLog($datos)
{
    echo <<<HTML
        <div class='log'>
        <table>
            <tr>
            <th>Fecha</th>
            <th>Acción</th>
            </tr>
        HTML;

    foreach ($datos as $dato) {
        echo '<tr>';
        echo '<td class="log_fecha">' . htmlentities($dato['fecha']) . '</td>';
        echo '<td class="log_accion">' . htmlentities($dato['accion']) . '</td>';
        echo '</tr>';
    }

    echo <<<HTML
        </table>
        </div>
        HTML;
}

$db = conexion();

$sql = "SELECT * FROM logs ORDER BY fecha DESC";
$datos = $db->query($sql);

verLog($datos);

desconexion($db);


?>