<?php
$pulgadasTexto = trim($_POST['pulgadas'] ?? '');
$resultado = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pulgadasTexto === '' || !is_numeric($pulgadasTexto)) {
        $error = 'Ingrese un valor numerico valido.';
    } else {
        $pulgadas = (float) $pulgadasTexto;
        $centimetros = $pulgadas * 2.54;
        $resultado = [
            'pulgadas' => $pulgadas,
            'centimetros' => $centimetros,
        ];
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conversor de pulgadas a centimetros</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Laboratorio 1</h1>
        <p>Conversion de pulgadas a centimetros.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <h2>Convertir pulgadas a centimetros</h2>
            <p class="muted">Formula usada: centimetros = pulgadas x 2.54</p>

            <form method="post" action="conversor_pulgadas.php">
                <label for="pulgadas">Pulgadas</label>
                <input
                    id="pulgadas"
                    name="pulgadas"
                    type="number"
                    step="0.01"
                    min="0"
                    value="<?php echo htmlspecialchars($pulgadasTexto); ?>"
                    required
                >
                <button type="submit">Convertir</button>
            </form>

            <?php if ($error !== ''): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($resultado !== null): ?>
                <div class="result">
                    <strong><?php echo number_format($resultado['pulgadas'], 2); ?> pulgadas</strong>
                    equivalen a
                    <strong><?php echo number_format($resultado['centimetros'], 2); ?> centimetros</strong>.
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
