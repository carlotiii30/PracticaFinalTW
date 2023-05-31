<?php
require('baseDatos.php'); // Conexión y desconexión
require('../funcion.php');

session_start();

// Datos del formulario

// - - - Comprobamos los datos recibidos - - - 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password1 = isset($_POST['password1']) ? $_POST['password1'] : '';
    $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
    $hayImagen = !empty($_FILES['images']['name']) ? true : false;

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

    if (!preg_match("/^[0-9]{9}$/", $telefono)) {
        $errores['telefono'] = "El teléfono no es correcto";
    }

    if (empty($direccion)) {
        $errores['direccion'] = "La dirección no puede estar vacía";
    }

    if ($password1 !== $password2) {
        $errores['contraseña'] = "Las contraseñas no coinciden";
    }



    // Si no hay errores, procesamos los datos.
    if (count($errores) === 0) {

        $id = $_SESSION['idUsuario'];

        // Conexión
        $db = conexion();

        // Actualizar usuario
        if(!$hayImagen){
            if(!empty($password1)){
                $query = "UPDATE usuarios SET nombre= ?, apellidos= ?, email= ?, telefono= ?, direccion=?, password=? WHERE id=?";
                $stmt = $db->prepare($query);
                $stmt->bind_param('ssssssi', $nombre, $apellidos, $email, $telefono, $direccion, $password1, $id);
            }else{
                $query = "UPDATE usuarios SET nombre= ?, apellidos= ?, email= ?, telefono= ?, direccion=? WHERE id=?";
                $stmt = $db->prepare($query);
                $stmt->bind_param('sssssi', $nombre, $apellidos, $email, $telefono, $direccion, $id);
            }
        }else{
            $image = file_get_contents($_FILES['images']['tmp_name']);
            if(!empty($password1)){
                $query = "UPDATE usuarios SET nombre= ?, apellidos= ?, email= ?, telefono= ?, direccion=?, password=?, foto = ? WHERE id=?";
                $stmt = $db->prepare($query);
                $stmt->bind_param('sssssssi', $nombre, $apellidos, $email, $telefono, $direccion, $password1, $image, $id);
            }else{
                $query = "UPDATE usuarios SET nombre= ?, apellidos= ?, email= ?, telefono= ?, direccion=?, foto = ? WHERE id=?";
                $stmt = $db->prepare($query);
                $stmt->bind_param('ssssssi', $nombre, $apellidos, $email, $telefono, $direccion, $image, $id);
            }
        }

        // Ejecutar la consulta
        if ($stmt->execute()) {

            // Mensaje de correcto ??

            // Insertar en el log
            insertarLog("El usuario $nombre ha modificado sus datos", $db);
            $stmt->close();
            // Redirigimos.
            header('Location: ../index.php');
            exit;
        } else {
            $registrado = "Error al actualizar el usuario";
            $stmt->close();
        }

        desconexion($db);


    } else {
        include('../modificarUsuario.php');
    }

} else {
    // Si se accede directamente a este archivo sin enviar el formulario, redirige al formulario.php
    header("Location: ../modificarUsuario.php");
    exit;
}
?>