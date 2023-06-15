<?php 
/**
 * Fichero con las funciones relacionadas con el registro de un usuario.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

/**
 * Función que procesa el formulario de registro de un usuario.
 * 
 * @global array $erroresRegistro Array que contiene los errores de validación del formulario.
 * @global bool $confirmado Indica si el registro ha sido confirmado.
 * @global bool $registrado Indica si el registro ha sido realizado.
 * 
 * @return void
 */
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
		$estado = isset($_POST['estado']) ? $_POST['estado'] : 'inactivo';

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

			// Utilizamos una sentencia preparada para insertar el usuario
			$sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion, rol, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $db->prepare($sql);
			$stmt->bind_param("ssssssss", $nombre, $apellidos, $email, $hash, $telefono, $direccion, $rol, $estado);

			// Ejecutar la consulta
			if ($stmt->execute()) {
				// Guardamos el usuario
				$_SESSION['usuario'] = $email;
				$_SESSION['nuevoUsuario'] = $stmt->insert_id;

				// Marcamos en el log
				insertarLog("¡Tenemos un nuevo usuario en la comunidad: $email!", $db);
				$registrado = true;

			} else {
				$registrado = false;
			}

			// Cerramos la declaración y la conexión con la base de datos
			$stmt->close();
			desconexion($db);
		}

		if (count($erroresRegistro) == 0) {
			$confirmado = true;
		}
	}
}

/**
 * Función que añade una foto de perfil a un usuario.
 * 
 * @param int $idUsuario ID del usuario al que se le añade la foto.
 * 
 * @return void
 */
function agregarFoto($idUsuario)
{
	// Conexión
	$db = conexion();

	$imagen = file_get_contents($_FILES['images']['tmp_name']);
	$_SESSION['imagen'] = $imagen;

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