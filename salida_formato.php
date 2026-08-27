<?php
$producto = 'Libro de PHP';
$precio = 18.756;
$cantidad = 3;
$total = $precio * $cantidad;
$mensaje = sprintf(
    'Producto: %s | Cantidad: %d | Total: B/. %0.2f',
    $producto,
    $cantidad,
    $total
);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salida con formato</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Salida con formato</h1>
        <p>Uso correcto de printf, sprintf y number_format.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <h2>Ejemplo correcto</h2>
            <p><?php echo htmlspecialchars($mensaje); ?></p>

            <h2>Regla importante</h2>
            <p>
                En printf se escribe una cadena con comodines y luego se pasan
                los valores separados por comas.
            </p>
            <pre>printf("Producto: %s | Cantidad: %d", $producto, $cantidad);</pre>

            <h2>Total con number_format</h2>
            <p>B/. <?php echo number_format($total, 2); ?></p>
        </section>
    </main>
</body>
</html>
