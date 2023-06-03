<?php
require('./vista/html/html.php'); // Maquetado de página
require "BD/guardarCambios.php";

htmlStart('Modificar usuario');
htmlNavGeneral('');
htmlEnd();


// - - - Mensajes - - - -
$mensajes = json_decode(file_get_contents('./vista/traducciones/formularioRegistro.json'), true);

$erroresCambios = array();

$datos = guardarCambios($_SESSION['idUsuario']);
 
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
                        <input type="file" name="images" <?php if($cambiosValidados)echo "disabled"; ?>> 
                    </label>
                    <label for="nombre">
                        <?php echo $mensajes[$idioma]["Nombre"]; ?>:
                    </label>
                    <input type="text" name="nombre" value="<?php if(!$cambiosValidados)echo $usuario['nombre']; else echo $datos['nombre']; ?>" <?php if($cambiosValidados)echo "readonly"; ?>>
                    <?php if (isset($erroresCambios['nombre'])) { ?>
						<p class="error">
							<?php echo $erroresCambios['nombre']; ?>
						</p>
					<?php } ?>

                    <label for="apellidos">
                        <?php echo $mensajes[$idioma]["Apellidos"]; ?>:
                    </label>
                    <input type="text" name="apellidos" value="<?php if(!$cambiosValidados)echo $usuario['apellidos']; else echo $datos['apellidos'];?>" <?php if($cambiosValidados)echo "readonly"; ?>>
                    <?php if (isset($erroresCambios['apellidos'])) { ?>
						<p class="error">
							<?php echo $erroresCambios['apellidos']; ?>
						</p>
					<?php } ?>

                    <label for="email">
                        <?php echo $mensajes[$idioma]["Email"]; ?>:
                    </label>
                    <input type="email" name="email" value="<?php if(!$cambiosValidados)echo $usuario['email']; else echo $datos['email'];?>" <?php if($cambiosValidados)echo "readonly"; ?>>
                    <?php if (isset($erroresCambios['email'])) { ?>
						<p class="error">
							<?php echo $erroresCambios['email']; ?>
						</p>
					<?php } ?>

                    <label for="telefono">
                        <?php echo $mensajes[$idioma]["Telefono"]; ?>:
                    </label>
                    <input type="text" name="telefono" value="<?php if(!$cambiosValidados)echo $usuario['telefono']; else echo $datos['telefono'];?>" <?php if($cambiosValidados)echo "readonly"; ?>>
                    <?php if (isset($erroresCambios['telefono'])) { ?>
						<p class="error">
							<?php echo $erroresCambios['telefono']; ?>
						</p>
					<?php } ?>

                    <label for="direccion">
                        <?php echo $mensajes[$idioma]["Direccion"]; ?>:
                    </label>
                    <input type="text" name="direccion" value="<?php if(!$cambiosValidados)echo $usuario['direccion']; else echo $datos['direccion'];?>" <?php if($cambiosValidados)echo "readonly"; ?>>
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
                            <input class="password1" type="password" name="password1" value="<?php if($cambiosValidados)echo $datos['password1'];?>" <?php if($cambiosValidados)echo "readonly"; ?>>
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
                            <input class="password2" type="password" name="password2" value="<?php if($cambiosValidados)echo $datos['password1'];?>" <?php if($cambiosValidados)echo "readonly"; ?>>
                        </div>
                    </div>

                    <label for="estado">
                        <?php echo $mensajes[$idioma]["Estado"]; ?>:
                    </label>
                    <input type="text" name="estado" value="<?php echo $usuario['estado']; ?>" disabled>

                    <label for="rol">Rol:</label>
                    <input type="text" name="rol" value="<?php echo $usuario['rol']; ?>" disabled>
                    
                    <div class="botones">
                    <?php if ($cambiosValidados == false) { ?>
                        <input type="submit" name="cambiar" value="Guardar cambios">
					<?php }else{?>
                        <input type="submit" name="confirmar" value="Confirmar cambios">
                        <?php if ($datos['hayimagen'] == true) { ?> <input type="hidden" name="imagen" value="<?php $datos['imagen'];?>"> <?php } ?>
                    <?php }?>
                    </div>
                </div>
            </form>
            <div class="imagen-usuario">
                <?php
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


/*function modificarUsuario($idUSuario){
    global $mensajes, $idioma;
    global $cambiosValidados;

    $erroresCambios = array();

    $datos = guardarCambios($idUSuario);
    
    // Conexión con la BBDD
    if (is_string($db = conexion())) {
        $msg_err = $db;
    } else {
        $id = $idUSuario;
        // Consulta SQL para obtener los datos del usuario
        $sql = "SELECT * FROM usuarios WHERE id = $id";
        $result = $db->query($sql);

        if ($result && $result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            
            $disabled = $cambiosValidados ? "disabled" : "";
            $readonly = $cambiosValidados ? "readonly" : "";

            echo <<<HTML
            <div class="modificar">
                <form method="POST" action=""  enctype="multipart/form-data">
                    <div class="entrada">
                        <label for="foto">
                            <input type="file" name="images" {$disabled}> 
                        </label>
                        <label for="nombre">
                            {$mensajes[$idioma]["Nombre"]}
                        </label>
            HTML;
                        echo '<input type="text" name="nombre" value="' . (!$cambiosValidados ? $usuario['nombre'] : $datos['nombre']) . '" ' . $readonly . '>';
                        if (isset($erroresCambios['nombre'])) {
                            echo '<p class="error">';
                                echo $erroresCambios['nombre'];
                            echo '</p>';
                        }   

                        echo '<label for="apellidos">';
                            echo $mensajes[$idioma]["Apellidos"];
                        echo '</label>';
                        echo '<input type="text" name="apellidos" value="' . (!$cambiosValidados ? $usuario['apellidos'] : $datos['apellidos']) . '" ' . $readonly . '>';
                        if (isset($erroresCambios['apellidos'])) {
                            echo '<p class="error">';
                                echo $erroresCambios['apellidos'];
                            echo '</p>';
                        }

                        echo '<label for="email">';
                            echo $mensajes[$idioma]["Email"];
                        echo '</label>';
                        echo '<input type="email" name="email" value="' . (!$cambiosValidados ? $usuario['email'] : $datos['email']) . '" ' . $readonly . '>';
                        if (isset($erroresCambios['email'])) {
                            echo '<p class="error">';
                                echo $erroresCambios['email'];
                            echo '</p>';
                        }

                        echo '<label for="telefono">';
                            echo $mensajes[$idioma]["Telefono"];
                        echo '</label>';
                        echo '<input type="text" name="telefono" value="' . (!$cambiosValidados ? $usuario['telefono'] : $datos['telefono']) . '" ' . $readonly . '>';
                        if (isset($erroresCambios['telefono'])) {
                            echo '<p class="error">';
                                echo $erroresCambios['telefono'];
                            echo '</p>';
                        }

                        echo '<label for="direccion">';
                            echo $mensajes[$idioma]["Direccion"];
                        echo '</label>';
                        echo '<input type="text" name="direccion" value="' . (!$cambiosValidados ? $usuario['direccion'] : $datos['direccion']) . '" ' . $readonly . '>';
                        if (isset($erroresCambios['direccion'])) {
                            echo '<p class="error">';
                                echo $erroresCambios['direccion'];
                            echo '</p>';
                        }

                        echo '<div class="contrasenia-contenedor">';
                            echo '<div class="campo">';
                                echo '<label for="password1">';
                                    echo $mensajes[$idioma]["Contrasenia"];
                                echo '</label>';
                                echo '<input class="password1" type="password" name="password1" value="' . (!$cambiosValidados ? $datos['password1'] : "") . '" ' . $readonly . '>';
                                if (isset($erroresCambios['contraseña'])) {
                                    echo '<p class="error">';
                                        echo $erroresCambios['contraseña'];
                                    echo '</p>';
                                }
                            echo '</div>';
                            echo '<div class="campo">';
                                echo '<label for="password2">';
                                    echo $mensajes[$idioma]["Confirmar"];
                                echo '</label>';
                                echo '<input class="password2" type="password" name="password2" value="' . (!$cambiosValidados ? $datos['password1'] : "") . '" ' . $readonly . '>';
                            echo '</div>';
                        echo '</div>';

                        echo '<label for="estado">';
                            echo $mensajes[$idioma]["Estado"];
                        echo '</label>';
                        echo '<input type="text" name="estado" value="' . ($usuario['estado']) . '" ' . $disabled . '>';

                        echo '<label for="rol">Rol:</label>';
                        echo '<input type="text" name="rol" value="' . ($usuario['rol']) . '" ' . $disabled . '>';
                        
                        <div class="botones">
                        <?php if ($cambiosValidados == false) { ?>
                            <input type="submit" name="cambiar" value="Guardar cambios">
                        <?php }else{?>
                            <input type="submit" name="confirmar" value="Confirmar cambios">
                            <?php if ($datos['hayimagen'] == true) { ?> <input type="hidden" name="imagen" value="<?php $datos['imagen'];?>"> <?php } ?>
                        <?php }?>
                        </div>
                    </div>
                </form>
                <div class="imagen-usuario">
                    <?php
                        descargarFoto("usuarios", $id, $db);
                    ?>
                </div>
            </div>
            HTML;

        } else {
            echo 'No se encontraron registros en la tabla usuario.';
        }


        // Desconectar de la BBDD (se puede omitir)
        desconexion($db);
    
}*/
?>