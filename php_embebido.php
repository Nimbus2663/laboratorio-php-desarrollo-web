<?php
$titulo = 'PHP embebido en HTML';
$fecha = date('d/m/Y');
$servidor = $_SERVER['SERVER_SOFTWARE'] ?? 'Servidor local';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($titulo); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($titulo); ?></h1>
        <p>Ejemplo de codigo PHP dentro de un documento HTML5.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <h2>Datos generados dinamicamente</h2>
            <p>Fecha actual del servidor: <strong><?php echo htmlspecialchars($fecha); ?></strong></p>
            <p>Software del servidor: <strong><?php echo htmlspecialchars($servidor); ?></strong></p>
        </section>
    </main>
</body>
</html>
