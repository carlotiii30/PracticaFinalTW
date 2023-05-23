<?php
include "cabecera.html";
?>


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

<?php include "piePagina.html"; ?>
</body>

</html>