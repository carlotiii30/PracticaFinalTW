<?php
function guardarCambios($idUsuario){
    global $erroresCambios;
    global $cambiosValidados;

    $datos = array();

    if(empty($_POST)){
        $cambiosValidados = false;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['editar'])) {
        
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
        $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password1 = isset($_POST['password1']) ? $_POST['password1'] : '';
        $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
        $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
        $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
        $hayImagen = !empty($_FILES['images']['name']) ? true : false;
        $rol = isset($_POST['rol']) ? $_POST['rol'] : '';
        $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    
        // - - - Validamos los datos - - - 
        if (empty($nombre)) {
            $erroresCambios['nombre'] = "El nombre no puede estar vacío";   
        }
        if (empty($apellidos)) {
            $erroresCambios['apellidos'] = "El apellido no puede estar vacío";
        }
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroresCambios['email'] = "El email no es correcto";
        }
    
        if (!preg_match("/^[0-9]{9}$/", $telefono)) {
            $erroresCambios['telefono'] = "El teléfono no es correcto";
        }
    
        if (empty($direccion)) {
            $erroresCambios['direccion'] = "La dirección no puede estar vacía";
        }
    
        if ($password1 !== $password2) {
            $erroresCambios['contraseña'] = "Las contraseñas no coinciden";
        }

    
    
    
        // Si no hay errores, procesamos los datos.
        if (count($erroresCambios) === 0) {
            $cambiosValidados = true;
            //Guardamos datos
            $datos['nombre'] = $nombre;
            $datos['apellidos'] = $apellidos;
            $datos['email'] = $email;
            $datos['telefono'] = $telefono;
            $datos['direccion'] = $direccion;
            $datos['password1'] = $password1;
            $datos['hayimagen'] = $hayImagen;
            $datos['rol'] = $rol;
            $datos['estado'] = $estado;
            if($hayImagen){
                $datos['imagen'] = file_get_contents($_FILES['images']['tmp_name']);
                $_SESSION['imagen'] = file_get_contents($_FILES['images']['tmp_name']);
            }

            if(isset($_POST['confirmar'])){
                if (isset($_POST['imagen'])){
                    $hayImagen = true;
                }
                $id = $idUsuario;;
        
                // Conexión
                $db = conexion();
        
                // Actualizar usuario
                if(!$hayImagen){
                    if(!empty($password1)){
                        $password1 = password_hash($password1, PASSWORD_BCRYPT);
                        $query = "UPDATE usuarios SET nombre= ?, apellidos= ?, email= ?, telefono= ?, direccion=?, password=?, estado=?, rol=? WHERE id=?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('ssssssssi', $nombre, $apellidos, $email, $telefono, $direccion, $password1, $estado, $rol, $id);
                    }else{
                        $query = "UPDATE usuarios SET nombre= ?, apellidos= ?, email= ?, telefono= ?, direccion=?, estado=?, rol=? WHERE id=?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('sssssssi', $nombre, $apellidos, $email, $telefono, $direccion, $estado, $rol, $id);
                    }
                }else{
                    //$image = file_get_contents($_FILES['images']['tmp_name']);
                    if(!empty($password1)){
                        $password1 = password_hash($password1, PASSWORD_BCRYPT);
                        $query = "UPDATE usuarios SET nombre= ?, apellidos= ?, email= ?, telefono= ?, direccion=?, password=?,  estado=?, rol=?, foto = ? WHERE id=?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('sssssssssi', $nombre, $apellidos, $email, $telefono, $direccion, $password1, $estado, $rol, $_SESSION['imagen'], $id);
                    }else{
                        $query = "UPDATE usuarios SET nombre= ?, apellidos= ?, email= ?, telefono= ?, direccion=?,  estado=?, rol=?, foto = ? WHERE id=?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('ssssssssi', $nombre, $apellidos, $email, $telefono, $direccion, $estado, $rol, $_SESSION['imagen'], $id);
                    }
                }
        
                // Ejecutar la consulta
                if ($stmt->execute()) {
                    // Insertar en el log
                    insertarLog("El usuario $email ha modificado sus datos", $db);
                    
                    //Actualizamos la variable de sesión con los nuevos datos
                    $_SESSION['nombreUsuario'] = $email;

                    // Mensaje de correcto
                    $_SESSION['mensaje'] = "¡Enhorabuena! Su información ha sido modificada con éxito.";
                    $stmt->close();

                    // Redirigimos.
                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION['mensaje'] = "Lo sentimos... No hemos podido modificar sus datos.";
                    $stmt->close();
                }
        
                desconexion($db); 
            }
        }
    }
    return $datos;
}
?>