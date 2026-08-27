<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hola Mundo en PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Practica 1: Iniciacion</h1>
        <p>Primer script PHP.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <?php
            echo '<h2>Hello World!</h2>';
            echo '<p>Este mensaje fue generado desde PHP.</p>';
            ?>
        </section>
    </main>
</body>
</html>
