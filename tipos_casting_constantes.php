<?php
$numeroEntero = 25;
$numeroDecimal = 3.75;
$texto = '42';
$logico = true;
$conversionInt = (int) $numeroDecimal;
$conversionFloat = (float) $texto;
$settypeEjemplo = '100';
settype($settypeEjemplo, 'integer');
define('PULGADA_EN_CM', 2.54);

ob_start();
var_dump($numeroDecimal, $logico);
$varDump = ob_get_clean();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tipos, casting y constantes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Tipos de datos, casting y constantes</h1>
        <p>Ejemplos de conversion de tipos en PHP.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <h2>Resultados</h2>
            <table>
                <tr><th>Expresion</th><th>Resultado</th></tr>
                <tr><td>gettype($numeroEntero)</td><td><?php echo gettype($numeroEntero); ?></td></tr>
                <tr><td>gettype($numeroDecimal)</td><td><?php echo gettype($numeroDecimal); ?></td></tr>
                <tr><td>(int) 3.75</td><td><?php echo $conversionInt; ?></td></tr>
                <tr><td>(float) '42'</td><td><?php echo $conversionFloat; ?></td></tr>
                <tr><td>settype('100', 'integer')</td><td><?php echo $settypeEjemplo . ' (' . gettype($settypeEjemplo) . ')'; ?></td></tr>
                <tr><td>Constante PULGADA_EN_CM</td><td><?php echo PULGADA_EN_CM; ?></td></tr>
            </table>

            <h2>var_dump</h2>
            <pre><?php echo htmlspecialchars($varDump); ?></pre>
        </section>
    </main>
</body>
</html>
