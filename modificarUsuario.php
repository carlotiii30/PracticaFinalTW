<?php
require('BD/baseDatos.php'); // Conexión y desconexión
require('./vista/html/html.php'); // Maquetado de página

htmlStart('Modificar usuario');
htmlNavGeneral('');
htmlEnd();

// - - - Mensajes - - - -
$mensajes = json_decode(file_get_contents('./vista/traducciones/formularioRegistro.json'), true);

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
            <form method="POST" action="BD/guardarCambios.php">
                <div class="imagen-usuario">
                    <?php
                    $fotoUsuario = base64_encode($usuario['foto']);
                    echo '<img src="data:image/jpeg;base64,' . $fotoUsuario . '" alt="Imagen de usuario">';
                    ?>
                </div>
                <div class="entrada">
                    <label for="nombre">
                        <?php echo $mensajes[$idioma]["Nombre"]; ?>:
                    </label>
                    <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>"><br>

                    <label for="apellidos">
                        <?php echo $mensajes[$idioma]["Apellidos"]; ?>:
                    </label>
                    <input type="text" name="apellidos" value="<?php echo $usuario['apellidos']; ?>"><br>

                    <label for="email">
                        <?php echo $mensajes[$idioma]["Email"]; ?>:
                    </label>
                    <input type="email" name="email" value="<?php echo $usuario['email']; ?>"><br>

                    <label for="telefono">
                        <?php echo $mensajes[$idioma]["Telefono"]; ?>:
                    </label>
                    <input type="text" name="telefono" value="<?php echo $usuario['telefono']; ?>"><br>

                    <label for="direccion">
                        <?php echo $mensajes[$idioma]["Direccion"]; ?>:
                    </label>
                    <input type="text" name="direccion" value="<?php echo $usuario['direccion']; ?>"><br>

                    <div class="contrasenia-contenedor">
                        <div class="campo">
                            <label for="password1">
                                <?php echo $mensajes[$idioma]["Contrasenia"]; ?>:
                            </label>
                            <input type="password" name="password1"><br>
                        </div>
                        <div class="campo">
                            <label for="password2">
                                <?php echo $mensajes[$idioma]["Confirmar"]; ?>:
                            </label>
                            <input type="password" name="password2"><br>
                        </div>
                    </div>

                    <label for="estado">
                        <?php echo $mensajes[$idioma]["Estado"]; ?>:
                    </label>
                    <input type="text" name="estado" value="<?php echo $usuario['estado']; ?>" disabled><br>

                    <label for="rol">Rol:</label>
                    <input type="text" name="rol" value="<?php echo $usuario['rol']; ?>" disabled><br>
                </div>

                <div class="botones">
                    <input type="submit" value="Guardar cambios">
                </div>
            </form>

        </div>
        <?php
    } else {
        echo 'No se encontraron registros en la tabla usuario.';
    }


    // Desconectar de la BBDD (se puede omitir)
    desconexion($db);
}
?>