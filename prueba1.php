<?php
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
        <button type="imagen" name="sumar" src="bien.png"> </button>
    </form>

</body>

</html>