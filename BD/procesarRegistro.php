<?php
// Datos de la conexión
require_once('credenciales.php');

// Datos del formulario

// - - - Comprobamos los datos recibidos - - - 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $contraseña = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';

    // Array para almacenar los errores que pueda haber.
    $errores = array();

    // - - - Validamos los datos - - - 
    if (empty($nombre)) {
        $errores['nombre'] = "El nombre no puede estar vacío";
    }
    if (empty($apellidos)) {
        $errores['apellidos'] = "El apellido no puede estar vacío";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = "El email no es correcto";
    }

    if (empty($contraseña)) {
        $errores['contraseña'] = "La contraseña no puede estar vacía";
    }

    if (!preg_match("/^[0-9]{9}$/", $telefono)) {
        $errores['telefono'] = "El teléfono no es correcto";
    }

    if (empty($direccion)) {
        $errores['direccion'] = "La dirección no puede estar vacía";
    }

    // Si no hay errores, procesamos los datos.
    if (count($errores) === 0) {

        // Conexión
        $db = new mysqli($host, $admin, $clave, $bbdd);

        if ($db) {
            // Crear usuario
            $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion) 
            VALUES ('$nombre', '$apellidos', '$email', '$contraseña', '$telefono', '$direccion')";

            // Ejecutar la consulta
            if ($db->query($sql) == TRUE) {
                // Guardamos el usuario
                $_SESSION['usuario'] = $email;

                // Redirigimos.
                header('Location: index.php');
                exit;
            } else {
                $registrado = "Error al crear el usuario";
            }

            mysqli_close($db);

        } else {
            echo "<p>Error de conexión</p>";
            echo "<p>Código: " . mysqli_connect_errno() . "</p>";
            echo "<p>Mensaje: " . mysqli_connect_error() . "</p>";
            die("Adiós");
        }


    } else {
        include('registrarUsuario.php');
    }

} else {
    // Si se accede directamente a este archivo sin enviar el formulario, redirige al formulario.php
    header("Location: registrarUsuario.php");
    exit;
}
?>