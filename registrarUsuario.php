<?php
/**
 * Fichero para mostrar la página de registrar un usuario.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

include('vista/html/html.php'); // Maquetado de página
require "BD/procesarRegistro.php";

htmlStart('Página de registro');
htmlNavGeneral('');

$registrado = false;
$confirmado = false;

// - - - Traducciones - - - 
$mensajes = json_decode(file_get_contents('./vista/traducciones/formularioRegistro.json'), true);

$erroresRegistro = array();

if (isset($_POST["enviar"]) || isset($_POST["confirmar"]))
	registrarUsuario();

if (isset($_POST["enviarFoto"])) {
	if ($_SESSION['nuevoUsuario'] != 0)
		agregarFoto($_SESSION['nuevoUsuario']);
}

?>

<head>
	<link rel="stylesheet" href="./vista/css/registro.css">
</head>

<body>
	<div class="registro">
		<h1>
			<?php echo $mensajes[$idioma]["DatosGeneral"]; ?>
		</h1>
		<form method="POST" action="">
			<div class="subform">
				<h1>
					<?php echo $mensajes[$idioma]["DatosPersonales"]; ?>
				</h1>
				<div class="datos">
					<div class="entrada">
						<label for="nombre">
							<?php echo $mensajes[$idioma]["Nombre"]; ?>
						</label>
						<input name="nombre" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>"
							<?php if ($confirmado)
								echo "readonly"; ?>>
						<?php if (isset($erroresRegistro['nombre'])) { ?>
							<p class="error">
								<?php echo $erroresRegistro['nombre']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="apellidos">
							<?php echo $mensajes[$idioma]["Apellidos"]; ?>
						</label>
						<input name="apellidos"
							value="<?php echo isset($_POST['apellidos']) ? $_POST['apellidos'] : ''; ?>" <?php if ($confirmado)
									   echo "readonly"; ?>>
						<?php if (isset($erroresRegistro['apellidos'])) { ?>
							<p class="error">
								<?php echo $erroresRegistro['apellidos']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="telefono">
							<?php echo $mensajes[$idioma]["Telefono"]; ?>
						</label>
						<input name="telefono"
							value="<?php echo isset($_POST['telefono']) ? $_POST['telefono'] : ''; ?>" <?php if ($confirmado)
									   echo "readonly"; ?>>
						<?php if (isset($erroresRegistro['telefono'])) { ?>
							<p class="error">
								<?php echo $erroresRegistro['telefono']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="direccion">
							<?php echo $mensajes[$idioma]["Direccion"]; ?>
						</label>
						<input name="direccion"
							value="<?php echo isset($_POST['direccion']) ? $_POST['direccion'] : ''; ?>" <?php if ($confirmado)
									   echo "readonly"; ?>>
						<?php if (isset($erroresRegistro['direccion'])) { ?>
							<p class="error">
								<?php echo $erroresRegistro['direccion']; ?>
							</p>
						<?php } ?>
					</div>
				</div>
				<div class="ayuda">
					<p>
						<?php echo $mensajes[$idioma]["AyudaPersonales"]; ?>
					</p>
				</div>
			</div>

			<div class="subform">
				<h1>
					<?php echo $mensajes[$idioma]["DatosSesion"]; ?>
				</h1>
				<div class="datos">
					<div class="entrada">
						<label for="email">
							<?php echo $mensajes[$idioma]["Email"]; ?>
						</label>
						<input name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" <?php if ($confirmado)
								   echo "readonly" ?>>
						<?php if (isset($erroresRegistro['email'])) { ?>
							<p class="error">
								<?php echo $erroresRegistro['email']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<div class="contrasenia-contenedor">
							<div class="campo">
								<label for="password1">
									<?php echo $mensajes[$idioma]["Contrasenia"]; ?>:
								</label>
								<input class="password1" type="password" name="password1"
									value="<?php echo $confirmado ? $_POST['password1'] : ''; ?>" <?php if ($confirmado)
											   echo "readonly" ?>>
								</div>
								<div class="campo">
									<label for="password2">
									<?php echo $mensajes[$idioma]["Confirmar"]; ?>:
								</label>
								<input class="password2" type="password" name="password2"
									value="<?php echo $confirmado ? $_POST['password2'] : ''; ?>" <?php if ($confirmado)
											   echo "readonly" ?>>
								</div>

							<?php if (isset($erroresRegistro['contraseña'])) { ?>
								<p class="error">
									<?php echo $erroresRegistro['contraseña']; ?>
								</p>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="ayuda">
					<p>
						<?php echo $mensajes[$idioma]["AyudaSesion"]; ?>
					</p>
				</div>
			</div>

			<?php if (isset($_SESSION['autenticado'])) {
				if ($_SESSION['rol'] == 'admin') { ?>
					<div class="subform">
						<h1>Usuario en el sistema</h1>
						<div class="datos">
							<div class="entrada">
								<label for="estado">
									<?php echo $mensajes[$idioma]["Estado"]; ?>:
								</label>
								<select name="estado" <?php if ($confirmado)
									echo "readonly" ?>>
										<option value="activo" <?php if (isset($_POST['estado']) && $_POST['estado'] === 'activo')
									echo 'selected'; ?>>Activo</option>
									<option value="inactivo" <?php if (isset($_POST['estado']) && $_POST['estado'] === 'inactivo')
										echo 'selected'; ?>>Inactivo</option>
								</select>
							</div>
							<div class="entrada">
								<label for="rol">Rol:</label>
								<select name="rol" <?php if ($confirmado)
									echo "readonly" ?>>
										<option value="colaborador" <?php if (isset($_POST['rol']) && $_POST['rol'] === 'colaborador')
									echo 'selected'; ?>>Colaborador</option>
									<option value="admin" <?php if (isset($_POST['rol']) && $_POST['rol'] === 'admin')
										echo 'selected'; ?>>Admin</option>
								</select>
							</div>
						</div>
					</div>
				<?php }
			} ?>

			<?php if (!$registrado) { ?>
				<div class="botones">
					<?php if (!$confirmado) { ?>
						<button name="enviar">
							<?php echo $mensajes[$idioma]["Enviar"]; ?>
						</button>
					<?php } else { ?>
						<button name="confirmar">
							<?php echo $mensajes[$idioma]["Validar"]; ?>
						</button>
					</div>
				<?php }
			} ?>
		</form>

		<?php if ($registrado) { ?>
			<form method="POST" action="" enctype="multipart/form-data">
				<div class="subform">
					<h1> Foto </h1>
					<div class="datos">
						<div class="entrada">
							<label for="images">
								Foto
							</label>
							<input type="file" name="images">
						</div>
					</div>
				</div>
				<div class="botones">
					<button name="enviarFoto">
						<?php echo $mensajes[$idioma]["Enviar"]; ?>
					</button>
				</div>
			</form>
		<?php } ?>
	</div>
</body>

</html>