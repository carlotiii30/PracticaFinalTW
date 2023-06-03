<?php
require('./vista/html/html.php'); // Maquetado de página
require "BD/guardarCambios.php";

htmlStart('Modificar usuario');
htmlNavGeneral('');
htmlEnd();

// - - - Mensajes - - - -
$mensajes = json_decode(file_get_contents('./vista/traducciones/formularioRegistro.json'), true);

$erroresCambios = array();

guardarCambios();
 
// Conexión con la BBDD
if (is_string($db = conexion())) {
    $msg_err = $db;
} else {
    $id = $_SESSION['idUsuario'];
    // Consulta SQL para obtener los datos del usuario
    $sql = "SELECT * FROM usuarios WHERE id = $id";
    $result = $db->query($sql);

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        ?>

        <div class="modificar">
            <form method="POST" action=""  enctype="multipart/form-data">
                <div class="entrada">
                    <label for="foto">
                        <input type="file" name="images"> 
                    </label>
                    <label for="nombre">
                        <?php echo $mensajes[$idioma]["Nombre"]; ?>:
                    </label>
                    <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>">
                    <?php if (isset($erroresCambios['nombre'])) { ?>
						<p class="error">
							<?php echo $erroresCambios['nombre']; ?>
						</p>
					<?php } ?>

                    <label for="apellidos">
                        <?php echo $mensajes[$idioma]["Apellidos"]; ?>:
                    </label>
                    <input type="text" name="apellidos" value="<?php echo $usuario['apellidos']; ?>">
                    <?php if (isset($erroresCambios['apellidos'])) { ?>
						<p class="error">
							<?php echo $erroresCambios['apellidos']; ?>
						</p>
					<?php } ?>

                    <label for="email">
                        <?php echo $mensajes[$idioma]["Email"]; ?>:
                    </label>
                    <input type="email" name="email" value="<?php echo $usuario['email']; ?>">
                    <?php if (isset($erroresCambios['email'])) { ?>
						<p class="error">
							<?php echo $erroresCambios['email']; ?>
						</p>
					<?php } ?>

                    <label for="telefono">
                        <?php echo $mensajes[$idioma]["Telefono"]; ?>:
                    </label>
                    <input type="text" name="telefono" value="<?php echo $usuario['telefono']; ?>">
                    <?php if (isset($erroresCambios['telefono'])) { ?>
						<p class="error">
							<?php echo $erroresCambios['telefono']; ?>
						</p>
					<?php } ?>

                    <label for="direccion">
                        <?php echo $mensajes[$idioma]["Direccion"]; ?>:
                    </label>
                    <input type="text" name="direccion" value="<?php echo $usuario['direccion']; ?>">
                    <?php if (isset($erroresCambios['direccion'])) { ?>
						<p class="error">
							<?php echo $erroresCambios['direccion']; ?>
						</p>
					<?php } ?>

                    <div class="contrasenia-contenedor">
                        <div class="campo">
                            <label for="password1">
                                <?php echo $mensajes[$idioma]["Contrasenia"]; ?>:
                            </label>
                            <input class="password1" type="password" name="password1">
                            <?php if (isset($erroresCambios['contraseña'])) { ?>
                                <p class="error">
                                    <?php echo $erroresCambios['contraseña']; ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="campo">
                            <label for="password2">
                                <?php echo $mensajes[$idioma]["Confirmar"]; ?>:
                            </label>
                            <input class="password2" type="password" name="password2">
                        </div>
                    </div>

                    <label for="estado">
                        <?php echo $mensajes[$idioma]["Estado"]; ?>:
                    </label>
                    <input type="text" name="estado" value="<?php echo $usuario['estado']; ?>" disabled>

                    <label for="rol">Rol:</label>
                    <input type="text" name="rol" value="<?php echo $usuario['rol']; ?>" disabled>
                    
                    <div class="botones">
                        <input type="submit" value="Guardar cambios">
                    </div>
                </div>
            </form>
            <div class="imagen-usuario">
                <?php
                    //header("Content-Type: text/html; charset=UTF-8");
                    descargarFoto("usuarios", $_SESSION["idUsuario"], $db);
                ?>
            </div>
        </div>
        <?php
    } else {
        echo 'No se encontraron registros en la tabla usuario.';
    }


    // Desconectar de la BBDD (se puede omitir)
    desconexion($db);
}
?>