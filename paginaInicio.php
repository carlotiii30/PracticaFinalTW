<!-- PAGINA INICIAL -->
<!-- probando ubuntu -->

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
    <title> Sal y quejate </title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <div class="elegirIdioma">
        <!-- Seleccionar idioma -->
        <p> <?php echo $mensajes[$idioma]["Lenguaje"]; ?> </p>
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
            <img src="./Imagenes/SugQueRec.png" alt="">
            <h1> Sal y quejate </h1>
        </section>
    </header>

    <nav class="menu">
        <ul>
            <li> <a href="#" class="activo"> <?php echo $mensajes[$idioma]["VerIncidencias"]; ?> </a> </li>
            <li> <a href="#"> <?php echo $mensajes[$idioma]["NuevaIncidencia"]; ?> </a> </li>
            <li> <a href="#"> <?php echo $mensajes[$idioma]["MisIncidencias"]; ?> </a> </li>
            <li> <a href="#"> <?php echo $mensajes[$idioma]["GestionUsuarios"]; ?> </a> </li>
            <li> <a href="#"> <?php echo $mensajes[$idioma]["Log"]; ?> </a> </li>
            <li> <a href="#"> <?php echo $mensajes[$idioma]["GestionBBDD"]; ?> </a> </li>
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
            <form action="">
                <div class="login">
                    <div class="entrada">
                        <label for="nombre"><?php echo $mensajes[$idioma]["Nombre"]; ?></label>
                        <input name="nombre" value="">
                    </div>
                    <div class="entrada">
                        <label for="Contraseña"><?php echo $mensajes[$idioma]["Contrasenia"]; ?></label>
                        <input name="contraseña" value="">
                    </div>
                </div>
                <div class="botones">
                    <input type="submit" name="Identificarse" <?php echo 'value="' . $mensajes[$idioma]["Identificarse"] . '"'; ?>>
                    <a href="registrarse"></a>
                </div>
            </form>
        </aside>
    </main>

    <footer>
        <p> Trabajo final de Tecnologías Web. (c) Carlota de la Vega Soriano y Manuel Vico Arboledas </p>
    </footer>
</body>

</html>