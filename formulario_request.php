<?php
$nombre = trim($_REQUEST['nombre'] ?? '');
$correo = trim($_REQUEST['correo'] ?? '');
$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario y REQUEST</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Formulario HTML y $_REQUEST</h1>
        <p>Captura datos enviados desde un formulario.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <form method="post" action="formulario_request.php">
                <label for="nombre">Nombre</label>
                <input id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">

                <label for="correo">Correo</label>
                <input id="correo" name="correo" type="email" value="<?php echo htmlspecialchars($correo); ?>">

                <button type="submit">Enviar</button>
            </form>

            <?php if ($nombre !== '' || $correo !== ''): ?>
                <div class="result">
                    <h2>Datos recibidos</h2>
                    <p>Metodo usado: <?php echo htmlspecialchars($metodo); ?></p>
                    <p>Nombre: <?php echo htmlspecialchars($nombre); ?></p>
                    <p>Correo: <?php echo htmlspecialchars($correo); ?></p>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
