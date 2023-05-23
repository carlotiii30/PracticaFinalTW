<?php 

function valor($n)
{
	if (!empty($_COOKIE[$n]))
		echo 'value="' . $_COOKIE[$n] . '"';
}

function mostrarErrores($error)
{
	if (!empty($_COOKIE[$error]))
		echo '<p class="error">' . $_COOKIE[$error] . '</p>';
} 

?>

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
						<input name="nombre" <?php valor('nombre'); ?> />
						<?php mostrarErrores('errorNombre'); ?>
					</div>
					<div class="entrada">
						<label for="apellidos">Apellidos</label>
						<input name="apellidos" <?php valor('apellidos'); ?> />
						<?php mostrarErrores('errorApellidos'); ?>
					</div>
					<div class="entrada">
						<label for="telefono">Teléfono</label>
						<input name="telefono" <?php valor('telefono'); ?> />
						<?php mostrarErrores('errorTelefono'); ?>
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
						<input name="direccion" <?php valor('direccion'); ?> />
						<?php mostrarErrores('errorDireccion'); ?>
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
						<input name="email" <?php valor('email'); ?> />
						<?php mostrarErrores('errorEmail'); ?>
					</div>
					<div class="entrada">
						<label for="contraseña">Contraseña</label>
						<input name="contraseña" <?php valor('contraseña'); ?> />
						<?php mostrarErrores('errorContraseña'); ?>
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