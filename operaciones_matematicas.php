<?php
$a = 20;
$b = 6;
$operaciones = [
    'Suma' => $a + $b,
    'Resta' => $a - $b,
    'Multiplicacion' => $a * $b,
    'Division' => $a / $b,
    'Modulo' => $a % $b,
    'Exponenciacion' => $a ** 2,
];
$precedencia1 = 2 + 3 * 4;
$precedencia2 = (2 + 3) * 4;
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Operaciones matematicas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Operadores matematicos</h1>
        <p>Operaciones aritmeticas y orden de precedencia.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <h2>Valores usados</h2>
            <p>$a = <?php echo $a; ?> y $b = <?php echo $b; ?></p>

            <table>
                <tr><th>Operacion</th><th>Resultado</th></tr>
                <?php foreach ($operaciones as $nombre => $resultado): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($nombre); ?></td>
                        <td><?php echo htmlspecialchars((string) round($resultado, 4)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h2>Precedencia</h2>
            <p>2 + 3 * 4 = <?php echo $precedencia1; ?></p>
            <p>(2 + 3) * 4 = <?php echo $precedencia2; ?></p>
        </section>
    </main>
</body>
</html>
