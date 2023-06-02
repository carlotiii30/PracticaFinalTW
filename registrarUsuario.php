<?php
include('vista/html/html.php'); // Maquetado de página
require "BD/procesarRegistro.php";

htmlStart('Página de registro');
htmlEnd();

// - - - Traducciones - - - 
$mensajes = json_decode(file_get_contents('./vista/traducciones/formularioRegistro.json'), true);

$erroresRegistro = array();

registrarUsuario();

?>

<head>
	<link rel="stylesheet" href="./vista/css/registro.css">
</head>

<body>
	<div class="registro">
		<h1>
			<?php echo $mensajes[$idioma]["DatosGeneral"]; ?>
		</h1>
		<form method="post" action="">
			<div class="subform">
				<h1>
					<?php echo $mensajes[$idioma]["DatosPersonales"]; ?>
				</h1>
				<div class="datos">
					<div class="entrada">
						<label for="nombre">
							<?php echo $mensajes[$idioma]["Nombre"]; ?>
						</label>
						<input name="nombre" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>">
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
							value="<?php echo isset($_POST['apellidos']) ? $_POST['apellidos'] : ''; ?>">
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
							value="<?php echo isset($_POST['telefono']) ? $_POST['telefono'] : ''; ?>">
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
							value="<?php echo isset($_POST['direccion']) ? $_POST['direccion'] : ''; ?>">
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
						<input name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
						<?php if (isset($erroresRegistro['email'])) { ?>
							<p class="error">
								<?php echo $erroresRegistro['email']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="contraseña">
							<?php echo $mensajes[$idioma]["Contrasenia"]; ?>
						</label>
						<input type="password" name="contraseña"
							value="<?php echo isset($_POST['contraseña']) ? $_POST['contraseña'] : ''; ?>">
						<?php if (isset($erroresRegistro['contraseña'])) { ?>
							<p class="error">
								<?php echo $erroresRegistro['contraseña']; ?>
							</p>
						<?php } ?>
					</div>
				</div>
				<div class="ayuda">
					<p>
						<?php echo $mensajes[$idioma]["AyudaSesion"]; ?>
					</p>
				</div>
			</div>
			<div class="botones">
				<input type="submit" name="enviar" value="<?php echo $mensajes[$idioma]["Enviar"]; ?>">
				<input type="reset" value="<?php echo $mensajes[$idioma]["Borrar"]; ?>">
			</div>
		</form>
	</div>
</body>

</html>