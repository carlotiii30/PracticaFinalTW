<?php
require('vista/html/html.php'); // Maquetado de página
require('BD/copiaSeguridad.php'); // Backup

// ************* Inicio de la página
htmlStart('Sal y quéjate');
htmlNavGeneral($mensajes[$idioma]["GestionBBDD"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = conexion();

    // Opción 1: Descargar copia de seguridad
    if (isset($_POST["descargar"])) {
        backup($db);
    }

    // Opción 2: Restaurar copia de seguridad
    else if (isset($_POST["restaurar"])) {
        //restaurar($db, );
    }

    // Opción 3: Borrar la BBDD (se reinicia)
    else if (isset($_POST["borrar"]) && isset($_POST["confirmar"]) && $_POST["confirmar"] === "si") {
        borrar($db);
    }

    desconexion($db);
}

?>

<div class="gestion">
    <form method="post" action="">
        <!-- Opción 1: Descargar copia de seguridad -->
        <div class="botones">
            <input type="submit" name="descargar" value="Descargar copia de seguridad">

            <!-- Opción 2: Restaurar copia de seguridad -->
            <input type="submit" name="restaurar" value="Restaurar copia de seguridad">

            <!-- Opción 3: Borrar la BBDD (se reinicia) -->
            <input type="submit" name="borrar" value="Borrar la base de datos">
        </div>

        <?php if (isset($_POST["borrar"])) { ?>
            <div class="seguridad">
                <p> Esta acción borrará toda la información que hay actualmente en la base de datos. ¿Está seguro/a de que
                    quiere realizarla? </p>
                <label for="confirmar"> Escriba "si" para confirmar el borrado: </label>
                <input type="text" name="confirmar" id="confirmar">
            </div>
        <?php } ?>
    </form>
</div>

<?php
htmlAside();
htmlEnd();
?>