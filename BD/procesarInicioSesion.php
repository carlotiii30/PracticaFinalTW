<?php
    require('baseDatos.php'); // Conexión y desconexión

    // Conexión con la BBDD
    if (is_string($db=conexion())) {
        $msg_err = $db;
    }else{
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            /******************Procesamiento******************/
            $usuario = $_POST['username'];
            $password = $_POST['password'];

            // Para prevenir inyección SQL
            $usuario = mysqli_real_escape_string($db, $usuario);
            $password = mysqli_real_escape_string($db, $password);

            $sql = "SELECT * FROM usuarios where email='$usuario' and password='$password'";
            $result = $db->query($sql);
            var_dump($result);
            if ($result && $result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
                session_start();
                $_SESSION['autenticado'] = true;
                $_SESSION['rol'] = $usuario["rol"];
                header("Location: index.php");
                //echo "inicio de sesion correcto";
            }else{
                // El inicio de sesión falló, muestra un mensaje de error
                echo $usuario;
                echo $password;
                echo "Usuario o contraseña incorrectos.";
            }
            
            // Desconectar de la BBDD (se puede omitir)
            desconexion($db);
        }
    }
?>