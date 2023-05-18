<!-- PAGINA INICIAL -->
<?php
include "codigoInicial.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title> Sal y quéjate </title>
    <link rel="stylesheet" href="./vista/css/estilos.css">
</head>

<body>

    <div class="elegirIdioma">
        <!-- Seleccionar idioma -->
        <img class="imgIdioma" src="./vista/imagenes/mundo_sf.png" alt="">
        <p>
            <?php echo $mensajes[$idioma]["Lenguaje"]; ?>
        </p>
        <form method="get" action="">
            <div class="entrada">
                <select name="idioma">
                    <option value="es" <?php seleccionado("idioma", "es") ?>>
                        <?php echo $mensajes[$idioma]["Espanol"]; ?>
                    </option>
                    <option value="en" <?php seleccionado("idioma", "en") ?>>
                        <?php echo $mensajes[$idioma]["Ingles"]; ?>
                    </option>
                    <option value="fr" <?php seleccionado("idioma", "fr") ?>>
                        <?php echo $mensajes[$idioma]["Frances"]; ?>
                    </option>
                </select>
            </div>
            <div class="botones">
                <input type="submit" name="aplicar" <?php echo 'value="' . $mensajes[$idioma]["Aplicar"] . '"'; ?>>
            </div>
        </form>
    </div>

    <header>
        <section class="cabecera">
            <img src="./vista/imagenes/SugQueRec.png" alt="">
            <h1> SAL Y QUÉJATE </h1>
        </section>
    </header>

    <nav class="menu">
        <ul>
            <?php
            foreach ($enlaces as $url => $clave) {
                $clase_activo = ($pagina_actual == $url) ? "activo" : "";
                echo '<li> <a href="' . $url . '" class="' . $clase_activo . '"> ' . $mensajes[$idioma][$clave] . ' </a> </li>';
            }
            ?>
        </ul>
    </nav>

    <main>
        <section class="principal">
        </section>

        <aside>
            <form action="">
                <div class="login">
                    <div class="entrada">
                        <label for="nombre">
                            <?php echo $mensajes[$idioma]["Nombre"]; ?>
                        </label>
                        <input name="nombre" value="">
                    </div>
                    <div class="entrada">
                        <label for="Contraseña">
                            <?php echo $mensajes[$idioma]["Contrasenia"]; ?>
                        </label>
                        <input name="contraseña" value="">
                    </div>
                </div>
                <div class="botones">
                    <input type="submit" name="Identificarse" <?php echo 'value="' . $mensajes[$idioma]["Identificarse"] . '"'; ?>>
                    <a href="./registrarse.html">
                        <?php echo $mensajes[$idioma]["Registrarse"]; ?>
                    </a>
                </div>
            </form>
        </aside>
    </main>

    <footer>
        <p> Trabajo final de Tecnologías Web. &copy; Carlota de la Vega Soriano y Manuel Vico Arboledas </p>
    </footer>
</body>

</html>