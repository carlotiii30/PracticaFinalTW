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
		if (isset($_POST['nombre'])) {
			$nombre = strip_tags($_POST['nombre']);
			$nombre = htmlentities($nombre, ENT_QUOTES);
		}

		if (isset($_POST['apellidos'])) {
			$apellidos = strip_tags($_POST['apellidos']);
			$apellidos = htmlentities($apellidos, ENT_QUOTES);
		}

		if (isset($_POST['email'])) {
			$email = strip_tags($_POST['email']);
			$email = htmlentities($email, ENT_QUOTES);
		}

		if (isset($_POST['password1'])) {
			$password1 = $_POST['password1'];
		}

		if (isset($_POST['password2'])) {
			$password2 = $_POST['password2'];
		}

		if (isset($_POST['telefono'])) {
			$telefono = strip_tags($_POST['telefono']);
			$telefono = htmlentities($telefono, ENT_QUOTES);
		}

		if (isset($_POST['direccion'])) {
			$direccion = strip_tags($_POST['direccion']);
			$direccion = htmlentities($direccion, ENT_QUOTES);
		}
		
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
			} else {
				if (existeEmail($email))
					$erroresRegistro['email'] = "El email ya existe";
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

	if (isset($_FILES['images']) && $_FILES['images']['error'] == UPLOAD_ERR_OK) {
		$imagen = file_get_contents($_FILES['images']['tmp_name']);
		$_SESSION['imagen'] = $imagen;

		if (subirFoto("usuarios", $db, $idUsuario)) {
			$_SESSION['mensaje'] = "¡Registrado y con foto!";
		} else {
			$_SESSION['mensaje'] = "Registrado, pero no se pudo subir la foto. Puede añadirla editando su perfil.";
		}
	} else {
		// Si no se proporciona ninguna imagen, establecer la imagen predeterminada
		$imagen = file_get_contents("./vista/imagenes/usuario.png");
		$_SESSION['imagen'] = $imagen;
		subirFoto("usuarios", $db, $idUsuario);
		$_SESSION['mensaje'] = "Registrado. Se le ha asignado una imagen por defecto. Puede modificarla en su perfil.";
	}

	desconexion($db);

	// Redirigimos.
	header('Location: index.php');
	exit;
}

/**
 * Función que comprueba si un email ya existe en la base de datos.
 * 
 * @param string $email Email a comprobar.
 * 
 * @return bool True si existe, false si no.
 */
function existeEmail($email)
{
	// Conexión
	$db = conexion();

	// Consulta
	$sql = "SELECT * FROM usuarios WHERE email = ?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $email);

	// Ejecutar la consulta
	$stmt->execute();
	$result = $stmt->get_result();

	// Cerramos la declaración y la conexión con la base de datos
	$stmt->close();
	desconexion($db);

	if ($result->num_rows > 0) {
		return true;
	} else {
		return false;
	}
}


?>