<?php
require('vista/html/html.php'); // Maquetado de página

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavAdmin($mensajes[$idioma]["NuevaIncidencia"]);
htmlAside(false);
htmlEnd();

// - - - Cargamos los mensajes - - -
$mensajes = json_decode(file_get_contents('./vista/traducciones/formularioNueva.json'), true);
?>

<div class="nueva">
    <h1 class="titulo">
        <?php echo $mensajes[$idioma]["Nueva"]; ?>
    </h1>
    <form method="post" action="./BD/procesarIncidencia.php">
        <h2 class="subtitulo">
            <?php echo $mensajes[$idioma]["Datos"]; ?>
        </h2>
        <div class="entrada">
            <label for="titulo">
                <?php echo $mensajes[$idioma]["Titulo"]; ?>
            </label>
            <input name="titulo" value="">

            <label for="descripcion">
                <?php echo $mensajes[$idioma]["Descripcion"]; ?>
            </label>
            <textarea name="descripcion" rows="4" cols="50"></textarea>

            <label for="lugar">
                <?php echo $mensajes[$idioma]["Lugar"]; ?>
            </label>
            <input name="lugar" value="">

            <label for="keywords">
                <?php echo $mensajes[$idioma]["PalabrasClave"]; ?>
            </label>
            <input name="keywords" value="">

        </div>
        <div class="botones">
            <input type="submit" value="<?php echo $mensajes[$idioma]["Enviar"]; ?>">
        </div>
    </form>
</div>