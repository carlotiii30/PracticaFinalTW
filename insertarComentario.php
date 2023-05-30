<?php
require('./vista/html/html.php'); // Maquetado de página

htmlStart('Introducir comentario');
htmlNavGeneral('');
htmlEnd();

// Conexión con la BBDD
if (is_string($db = conexion())) {
    $msg_err = $db;
} else {
    // Id del usuario
    $id = $_SESSION['idUsuario'];

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';

    $sql = "INSERT INTO comentarios (idUsuario, idIncidencia, comentario, fecha) VALUES ($id, $idIncidencia, '$comentario', NOW())";

    // Ejecutar la consulta
    if ($db->query($sql) == TRUE) {
        insertarLog("El usuario $nombre ha comentado en la incidencia con id $idIncidencia", $db);
        // Redirigimos.
        header('Location: index.php');
        exit;
    }
}

// Desconectar de la BBDD 
desconexion($db);

?>