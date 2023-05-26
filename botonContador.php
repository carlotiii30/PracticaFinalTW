<?php
# En realidad no he cambiado nada
session_start();

if (!isset($_SESSION['contador'])) {
    $_SESSION['contador'] = 0;
}

if (isset($_POST['sumar'])) {
    $_SESSION['contador']++;

    header("Location: {$_SERVER['SCRIPT_NAME']}", true, 303);
}

echo "Contador: " . $_SESSION['contador'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Ejemplo</title>
</head>

<body>
    <form method="post">
        <button name="sumar">
            <img src="./Imagenes/verde.png">
            <em>hola</em>
        </button>
    </form>

</body>

</html>
