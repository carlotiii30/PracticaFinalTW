<?php

function registrarUsuario()
{
	global $mensajes;
	global $idioma;
	global $erroresRegistro;
	global $confirmado;

	// - - - Comprobamos los datos recibidos - - - 
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
		$apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$password1 = isset($_POST['password1']) ? $_POST['password1'] : '';
		$password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
		$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
		$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';

		$rol = "colaborador";

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

		if ($confirmado) {
			// Conexión
			$db = conexion();

			// Crear usuario
			$sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion, rol) 
					VALUES ('$nombre', '$apellidos', '$email', '$password1', '$telefono', '$direccion', '$rol')";

			// Ejecutar la consulta
			if ($db->query($sql) === TRUE) {
				// Guardamos el usuario
				$_SESSION['usuario'] = $email;

				// Marcamos en el log
				insertarLog("¡Tenemos un nuevo usuario en la comunidad: $email!", $db);

				// Redirigimos.
				header('Location: index.php');
				exit;
			} else {
				$registrado = "Error al crear el usuario";
			}

			desconexion($db);
		}

		if (count($erroresRegistro) === 0) {
			$confirmado = true;
		}

	}
}

?>