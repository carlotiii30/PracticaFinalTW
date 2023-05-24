<?php
require('vista/html/html.php'); // Maquetado de página

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavAdmin('');
htmlAside(false);
htmlEnd();

// - - - Cargamos los mensajes - - -
$mensajes = json_decode(file_get_contents('./vista/traducciones/formularioCriterios.json'), true);
?>


<div class="criterios">
    <h1>
        <?php echo $mensajes[$idioma]["Titulo"]; ?>
    </h1>
    <form method="post" action="./BD/procesarCriterios.php">
        <h1>
            <?php echo $mensajes[$idioma]["Criterios"]; ?>
        </h1>
        <div class="entrada">
            <fieldset>
                <legend>
                    <?php echo $mensajes[$idioma]["Ordenar"] ?>
                </legend>
                <label><input type="radio" name="ordenar" value="Antiguedad">
                    <?php echo $mensajes[$idioma]["Antiguedad"] ?>
                </label>
                <label><input type="radio" name="ordenar" value="Mg">
                    <?php echo $mensajes[$idioma]["MeGustas"] ?>
                </label>
                <label><input type="radio" name="ordenar" value="NoMg">
                    <?php echo $mensajes[$idioma]["NoMeGustas"] ?>
                </label>
            </fieldset>
        </div>
        <div class="entrada">
            <fieldset>
                <legend>
                    <?php echo $mensajes[$idioma]["Incidencias"] ?>
                </legend>
                <label for="texto">
                    <?php echo $mensajes[$idioma]["Texto"]; ?>
                </label>
                <input name="texto" value="">

                <label for="lugar">
                    <?php echo $mensajes[$idioma]["Lugar"]; ?>
                </label>
                <input name="lugar" value="">
            </fieldset>
        </div>
        <div class="entrada">
            <fieldset>
                <legend>
                    <?php echo $mensajes[$idioma]["Estado"] ?>
                </legend>
                <label> <input type="checkbox" name="estado[]" value="pendiente">
                    <?php echo $mensajes[$idioma]["Pendiente"]; ?>
                </label>
                <label> <input type="checkbox" name="estado[]" value="comprobada">
                    <?php echo $mensajes[$idioma]["Comprobada"]; ?>
                </label>
                <label> <input type="checkbox" name="estado[]" value="tramitada">
                    <?php echo $mensajes[$idioma]["Tramitada"]; ?>
                </label>
                <label> <input type="checkbox" name="estado[]" value="irresoluble">
                    <?php echo $mensajes[$idioma]["Irresoluble"]; ?>
                </label>
                <label> <input type="checkbox" name="estado[]" value="resuelta">
                    <?php echo $mensajes[$idioma]["Resuelta"]; ?>
                </label>
            </fieldset>
        </div>
        <div class="botones">
            <input type="submit" value="<?php echo $mensajes[$idioma]["Aplicar"]; ?>">
        </div>
    </form>
</div>