# Código que crea un botón un contador. Cada vez que se pulsa el botón aumenta el contador
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
        <button name="sumar" style="border: double;" width=10%>
            <img src="./Imagenes./verde.png" width=100%>
        </button>
    </form>

</body>

</html>