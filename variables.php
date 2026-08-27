<?php
$author1 = 'John Doe';
$author2 = 'Max Mustermann';
$materia = 'Introduccion al lenguaje PHP';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Variables en PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Variables</h1>
        <p>Ejemplo basico de declaracion y salida de variables.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <h2>Hello World!</h2>
            <p>
                Esta pagina dinamica fue creada por
                <strong><?php echo htmlspecialchars($author1); ?></strong>
                y
                <strong><?php echo htmlspecialchars($author2); ?></strong>.
            </p>
            <p>Materia: <?php echo htmlspecialchars($materia); ?></p>
        </section>
    </main>
</body>
</html>
