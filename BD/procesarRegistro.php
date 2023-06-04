<?php

function registrarUsuario()
{
	global $erroresRegistro;
	global $confirmado;
	global $registrado;

	// - - - Comprobamos los datos recibidos - - - 
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
		$apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$password1 = isset($_POST['password1']) ? $_POST['password1'] : '';
		$password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
		$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
		$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';

		$rol = isset($_POST['rol']) ? $_POST['rol'] : 'colaborador';
		$estado = isset($_POST['estado']) ? $_POST['estado'] : 'activo';

		if (isset($_POST['enviar'])) {
			// - - - Validamos los datos - - - 
			if (empty($nombre)) {
				$erroresRegistro['nombre'] = "El nombre no puede estar vacío";
			}

			if (empty($apellidos)) {
				$erroresRegistro['apellidos'] = "El apellido no puede estar vacío";
			}

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$erroresRegistro['email'] = "El email no es correcto";
			}

			if ($password1 !== $password2) {
				$erroresRegistro['contraseña'] = "Las contraseñas no coinciden";
			}

			if (!preg_match("/^[0-9]{9}$/", $telefono)) {
				$erroresRegistro['telefono'] = "El teléfono no es correcto";
			}

			if (empty($direccion)) {
				$erroresRegistro['direccion'] = "La dirección no puede estar vacía";
			}
		} else if (isset($_POST['confirmar'])) {

			// Ciframos la contraseña
			$hash = password_hash($password1, PASSWORD_BCRYPT);

			// Conexión
			$db = conexion();

			// Crear usuario
			$sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion, rol, estado) 
                    VALUES ('$nombre', '$apellidos', '$email', '$hash', '$telefono', '$direccion', '$rol', '$estado')";

			// Ejecutar la consulta
			if ($db->query($sql) === TRUE) {
				// Guardamos el usuario
				$_SESSION['usuario'] = $email;
				$_SESSION['nuevoUsuario'] = $db->insert_id;

				// Marcamos en el log
				insertarLog("¡Tenemos un nuevo usuario en la comunidad: $email!", $db);
				$registrado = true;

			} else {
				$registrado = false;
			}

			desconexion($db);
		}

		if (count($erroresRegistro) == 0) {
			$confirmado = true;
		}
	}

}

function agregarFoto($idUsuario)
{
	// Conexión
	$db = conexion();

	$_SESSION['imagen'] = file_get_contents($_FILES['images']['tmp_name']);

	if (subirFoto("usuarios", $db, $idUsuario)) {
		$_SESSION['mensaje'] = "¡Registrado y con foto!";
		desconexion($db);
	}
	else {
		$_SESSION['mensaje'] = "Registrado, pero sin foto... Puede añadirla editando su perfil.";
	}
	
    // Redirigimos.
    header('Location: index.php');
	exit;

}

?>