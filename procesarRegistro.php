<?php
// Datos de la conexión
$host = "localhost";
$admin = "carlotadlavega2223";
$clave = "nQ69ZPy3";
$bbdd = "carlotadlavega2223";

// Datos del formulario

// - - - Comprobamos los datos recibidos - - - 
if (isset($_POST['nombre'])) {
    $nombre = strip_tags($_POST['nombre']);
    htmlentities($nombre, ENT_QUOTES);
}

if (isset($_POST['apellidos'])) {
    $apellidos = strip_tags($_POST['apellidos']);
}

if (isset($_POST['email']))
    $email = $_POST['email'];

if (isset($_POST['contraseña']))
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

if (isset($_POST['telefono']))
    $telefono = $_POST['telefono'];

if (isset($_POST['direccion']))
    $direccion = $_POST['direccion'];

// Array para almacenar los errores que pueda haber.
$errores = array();

// - - - Validamos los datos - - - 
if (empty($nombre)) {
    $errores['nombre'] = "El nombre no puede estar vacío";
    setcookie('errorNombre', $errores['nombre']);
} else
    setcookie('nombre', $nombre);

if (empty($apellidos)) {
    $errores['apellidos'] = "El apellido no puede estar vacío";
    setcookie('errorApellidos', $errores['apellidos']);
} else
    setcookie('apellidos', $apellidos);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores['email'] = "El email no es correcto";
    setcookie('errorEmail', $errores['email']);
} else
    setcookie('email', $email);

if (empty($contraseña)) {
    $errores['contraseña'] = "La contraseña no puede estar vacía";
    setcookie('errorContraseña', $errores['contraseña']);
} else
    setcookie('contraseña', $contraseña);

if (!preg_match("/^[0-9]{9}$/", $telefono)) {
    $errores['telefono'] = "El teléfono no es correcto";
    setcookie('errorTelefono', $errores['telefono']);
} else
    setcookie('telefono', $telefono);

if (empty($direccion)) {
    $errores['direccion'] = "La dirección no puede estar vacía";
    setcookie('errorDireccion', $errores['direccion']);
} else
    setcookie('direccion', $direccion);

// Si hay algún error, redirigimos al formulario.
if (!empty($errores)) {
    header('Location: registrarse.php');
    exit;
}

// Conexión
$db = new mysqli($host, $admin, $clave, $bbdd);

if ($db) {
    // Crear usuario
    $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion) 
            VALUES ('$nombre', '$apellidos', '$email', '$contraseña', '$telefono', '$direccion')";

    // Ejecutar la consulta
    if ($db->query($sql) == TRUE) {
        $registrado = "Usuario creado correctamente";

        // Borramos las cookies relacionadas con el formulario
        foreach ($_COOKIE as $nombre => $valor) {
            if (strpos($nombre, 'error') !== false || in_array($nombre, ['nombre', 'apellidos', 'email', 'contraseña', 'telefono', 'direccion'])) {
                unset($_COOKIE[$nombre]);
                setcookie($nombre, '', time() - 3600, '/');
            }
        }


        header('Location: paginaInicio.php');
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

?>