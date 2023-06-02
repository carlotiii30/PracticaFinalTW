<?php
require('./vista/html/html.php'); // Maquetado de página

htmlStart('Introducir comentario');
htmlNavGeneral('');
htmlEnd();

// Conexión con la BBDD
$db = conexion();
if (is_string($db)) {
    $msg_err = $db;
} else {
    // Id del usuario
    if (isset($_SESSION['idUsuario']))
        $id = $_SESSION['idUsuario'];
    else
        $id = 0;

    // Nombre
    $nombre = obtenerNombreUsuario($id);

    // Id de la incidencia
    $idIncidencia = $_SESSION['idIncidencia'];

    ?>

    <div class="comentar">
        <form method="POST" action="">
            <label for="comentario">
                Comentario:
            </label>
            <textarea name="comentario" rows="4" cols="50"></textarea>
            <div class="botones">
                <input type="submit" value="Enviar comentario">
            </div>
        </form>
    </div>

    <?php
}

// Mostrar mensaje si el comentario está vacío
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['comentario'])) {
    echo "<p class='error'>No puede insertar un comentario vacío. Por favor, introduzca un comentario.</p>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';

    // Verificar si el comentario no está vacío
    if (!empty($comentario)) {
        $nombreUsuario = isset($nombre) ? $nombre : 'Anónimo';

        // Escapar los valores para prevenir inyección SQL
        $id = $db->real_escape_string($id);
        $idIncidencia = $db->real_escape_string($idIncidencia);
        $comentario = $db->real_escape_string($comentario);

        $sql = "INSERT INTO comentarios (idUsuario, idIncidencia, comentario, fecha) VALUES ($id, $idIncidencia, '$comentario', NOW())";

        // Ejecutar la consulta
        if ($db->query($sql) === TRUE) {
            insertarLog("El usuario $nombreUsuario ha comentado en la incidencia con id $idIncidencia", $db);
            // Mostrar mensaje de éxito
            // Redirigimos.
            header('Location: index.php');
            exit;
        }
    }
}

// Desconectar de la BBDD 
desconexion($db);
?>