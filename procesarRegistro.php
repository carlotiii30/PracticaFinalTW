<?php

// Datos de la conexión
$host = "localhost";
$admin = "carlotadlavega2223";
$clave = "nQ69ZPy3";
$bbdd = "carlotadlavega2223";

// Datos del formulario
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

// Conexión
$db = new mysqli($host, $admin, $clave, $bbdd);

if ($db) {
    // Crear usuario
    $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion) 
            VALUES ('$nombre', '$apellidos', '$email', '$contraseña', '$telefono', '$direccion')";

    // Ejecutar la consulta
    if ($db->query($sql) == TRUE) {
        $registrado = "Usuario creado correctamente";
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