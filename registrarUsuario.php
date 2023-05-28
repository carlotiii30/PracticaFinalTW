<?php
require('./vista/html/html.php'); // Maquetado de página

htmlStart('Página de registro');
htmlEnd();

?>

<head>
	<link rel="stylesheet" href="./vista/css/registro.css">
</head>

<body>
	
	<div class="registro">
		<h1>
			<?php echo $mensajes[$idioma]["DatosGeneral"]; ?>
		</h1>
		<form method="post" action="./BD/procesarRegistro.php">
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
						<?php if (isset($errores['nombre'])) { ?>
							<p class="error">
								<?php echo $errores['nombre']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="apellidos">
							<?php echo $mensajes[$idioma]["Apellidos"]; ?>
						</label>
						<input name="apellidos"
							value="<?php echo isset($_POST['apellidos']) ? $_POST['apellidos'] : ''; ?>">
						<?php if (isset($errores['apellidos'])) { ?>
							<p class="error">
								<?php echo $errores['apellidos']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="telefono">
							<?php echo $mensajes[$idioma]["Telefono"]; ?>
						</label>
						<input name="telefono"
							value="<?php echo isset($_POST['telefono']) ? $_POST['telefono'] : ''; ?>">
						<?php if (isset($errores['telefono'])) { ?>
							<p class="error">
								<?php echo $errores['telefono']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="direccion">
							<?php echo $mensajes[$idioma]["Direccion"]; ?>
						</label>
						<input name="direccion"
							value="<?php echo isset($_POST['direccion']) ? $_POST['direccion'] : ''; ?>">
						<?php if (isset($errores['direccion'])) { ?>
							<p class="error">
								<?php echo $errores['direccion']; ?>
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
						<?php if (isset($errores['email'])) { ?>
							<p class="error">
								<?php echo $errores['email']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="contraseña">
							<?php echo $mensajes[$idioma]["Contrasenia"]; ?>
						</label>
						<input name="contraseña"
							value="<?php echo isset($_POST['contraseña']) ? $_POST['contraseña'] : ''; ?>">
						<?php if (isset($errores['contraseña'])) { ?>
							<p class="error">
								<?php echo $errores['contraseña']; ?>
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
				<input type="submit" value="<?php echo $mensajes[$idioma]["Enviar"]; ?>">
				<input type="reset" value="<?php echo $mensajes[$idioma]["Borrar"]; ?>">
			</div>
		</form>
	</div>

</body>

</html>