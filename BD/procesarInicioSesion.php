<?php
/**
 * Fichero que procesa el formulario inicio de sesión
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

require('baseDatos.php'); // Conexión y desconexión
require('../funcion.php');

// Conexión con la BBDD
if (is_string($db = conexion())) {
    $msg_err = $db;
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        /******************Procesamiento******************/
        $usuario = $_POST['username'];
        $nombreUsuario = $usuario;
        $password = $_POST['password'];

        // Para prevenir inyección SQL
        $usuario = mysqli_real_escape_string($db, $usuario);
        $password = mysqli_real_escape_string($db, $password);

        // Continuamos
        $sql = "SELECT * FROM usuarios WHERE email='$usuario'";
        $result = $db->query($sql);

        if ($result && $result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            $hash = $usuario['password'];
            if(password_verify($password, $hash) && $usuario['estado'] == "activo") {
                $idUsuario = $usuario["id"];
                session_start();
                $_SESSION['autenticado'] = true;
                $_SESSION['rol'] = $usuario["rol"];
                $_SESSION['nombreUsuario'] = $nombreUsuario;
                $_SESSION['idUsuario'] = $idUsuario;

                insertarLog("El usuario $nombreUsuario ha iniciado sesión", $db);

                header("Location: ../index.php");
            } else {
                // El inicio de sesión falló
                header("Location: ../index.php");
                insertarLog("El usuario $nombreUsuario ha intentado iniciar sesión sin éxito.", $db);
            }
        } else {
            // El inicio de sesión falló
            header("Location: ../index.php");
            insertarLog("El usuario $nombreUsuario ha intentado iniciar sesión sin éxito.", $db);
        }

        // Desconectar de la BBDD (se puede omitir)
        desconexion($db);
    }
}
?>
