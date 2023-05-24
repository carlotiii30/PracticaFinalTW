<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title> Página de registro </title>
	<link rel="stylesheet" href="./vista/css/estilos.css">
	<link rel="stylesheet" href="./vista/css/registro.css">
</head>

<body>
	<div class="registro">
		<h1>Datos del usuario</h1>
		<form method="post" action="procesarRegistro.php">
			<div class="subform">
				<h1>Datos personales</h1>
				<div class="datos">
					<div class="entrada">
						<label for="nombre">Nombre</label>
						<input name="nombre" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>">
						<?php if (isset($errores['nombre'])) { ?>
							<p class="error">
								<?php echo $errores['nombre']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="apellidos">Apellidos</label>
						<input name="apellidos" value="<?php echo isset($_POST['apellidos']) ? $_POST['apellidos'] : ''; ?>">
						<?php if (isset($errores['apellidos'])) { ?>
							<p class="error">
								<?php echo $errores['apellidos']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="telefono">Teléfono</label>
						<input name="telefono" value="<?php echo isset($_POST['telefono']) ? $_POST['telefono'] : ''; ?>">
						<?php if (isset($errores['telefono'])) { ?>
							<p class="error">
								<?php echo $errores['telefono']; ?>
							</p>
						<?php } ?>
					</div>
				</div>
				<div class="ayuda">
					<p> Datos personales del usuario. Son obligatorios.</p>
				</div>
			</div>
			<div class="subform">
				<h1>Dirección postal</h1>
				<div class="datos">
					<div class="entrada">
						<label for="direccion">Dirección</label>
						<input name="direccion" value="<?php echo isset($_POST['direccion']) ? $_POST['direccion'] : ''; ?>">
						<?php if (isset($errores['direccion'])) { ?>
							<p class="error">
								<?php echo $errores['direccion']; ?>
							</p>
						<?php } ?>
					</div>
				</div>
				<div class="ayuda">
					<p>Datos relacionados con su dirección. Así podemos cerciorar que es un vecino de la
						comunidad. Son obligatorios.</p>
				</div>
			</div>
			<div class="subform">
				<h1>Inicio sesión</h1>
				<div class="datos">
					<div class="entrada">
						<label for="email">Email</label>
						<input name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
						<?php if (isset($errores['email'])) { ?>
							<p class="error">
								<?php echo $errores['email']; ?>
							</p>
						<?php } ?>
					</div>
					<div class="entrada">
						<label for="contraseña">Contraseña</label>
						<input name="contraseña" value="<?php echo isset($_POST['contraseña']) ? $_POST['contraseña'] : ''; ?>">
						<?php if (isset($errores['contraseña'])) { ?>
							<p class="error">
								<?php echo $errores['contraseña']; ?>
							</p>
						<?php } ?>
					</div>
				</div>
				<div class="ayuda">
					<p>Datos para iniciar sesión. Son obligatorios.</p>
				</div>
			</div>
			<div class="botones">
				<input type="submit" value="Enviar datos">
				<input type="reset" value="Borrar formulario">
			</div>
		</form>
	</div>

</body>

</html>