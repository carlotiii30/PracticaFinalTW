<!-- PAGINA INICIAL -->
<?php

// - - - Cargamos los mensajes - - -
$mensajes = json_decode(file_get_contents('traducciones.json'), true);

// - - - Comprobamos si el formulario se ha enviado - - -
if (isset($_GET) and !empty($_GET)) {

    if (isset($_GET["idioma"]) and isset($_GET["aplicar"])) {
        $idioma = $_GET["idioma"];
        setcookie("idioma", $idioma);
    } else {
        $idioma = $_COOKIE["idioma"];
    }

} else {
    // Inicializamos la variable idioma
    $idioma = "es";
}

// - - - Funcion que comprueba si está seleccionado para marcarlo - - - -
function seleccionado($n, $v)
{
    if (isset($_GET[$n]) and ($_GET[$n] == $v))
        echo 'selected';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title> Siempre quejandome </title>
</head>

<body>

    <div>
        <!-- Seleccionar idioma -->
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
        <section>
            <img src="" alt="">
            <h1> Sal y quejate </h1>
        </section>
    </header>

    <nav>
        <ul>
            <li>Ver incidencias</li>
            <li>Nueva incidencia</li>
            <li>Mis incidencias</li>
            <li>Gestión de usuarios</li>
            <li>Ver Log</li>
            <li>Gestion BBDD</li>
        </ul>
    </nav>

    <main>
        <section>
            <!-- Mostrar mensaje de bienvenida en el idioma seleccionado -->
            <h1>
                <?php echo $mensajes[$idioma]["Bienvenida"]; ?>
            </h1>
            <p>
                <?php echo $mensajes[$idioma]["Inicio"]; ?>
            </p>
            <p>
                <?php echo $mensajes[$idioma]["Informacion"]; ?>
            </p>
        </section>

        <aside>
            <div class="login">
                <select name="login_select">
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
        </aside>
    </main>

    <footer>
        <p> Pie de página</p>
    </footer>
</body>

</html>