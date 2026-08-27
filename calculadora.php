<?php
$numero1Texto = trim($_POST['numero1'] ?? '');
$numero2Texto = trim($_POST['numero2'] ?? '');
$operacion = $_POST['operacion'] ?? 'sumar';
$decimalesTexto = trim($_POST['decimales'] ?? '2');
$resultado = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiereNumero2 = $operacion !== 'redondear';

    if ($numero1Texto === '' || !is_numeric($numero1Texto)) {
        $error = 'Ingrese un primer numero valido.';
    } elseif ($requiereNumero2 && ($numero2Texto === '' || !is_numeric($numero2Texto))) {
        $error = 'Ingrese un segundo numero valido.';
    } elseif ($decimalesTexto === '' || !ctype_digit($decimalesTexto)) {
        $error = 'Ingrese una cantidad valida de decimales.';
    } else {
        $numero1 = (float) $numero1Texto;
        $numero2 = (float) $numero2Texto;
        $decimales = (int) $decimalesTexto;

        switch ($operacion) {
            case 'sumar':
                $resultado = $numero1 + $numero2;
                break;
            case 'restar':
                $resultado = $numero1 - $numero2;
                break;
            case 'multiplicar':
                $resultado = $numero1 * $numero2;
                break;
            case 'redondear':
                $resultado = round($numero1, $decimales);
                break;
            default:
                $error = 'Seleccione una operacion valida.';
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calculadora en PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Problema 2: Calculadora</h1>
        <p>Suma, resta, multiplicacion y redondeo de decimales.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <form method="post" action="calculadora.php">
                <label for="numero1">Numero 1</label>
                <input
                    id="numero1"
                    name="numero1"
                    type="number"
                    step="any"
                    value="<?php echo htmlspecialchars($numero1Texto); ?>"
                    required
                >

                <label for="numero2">Numero 2</label>
                <input
                    id="numero2"
                    name="numero2"
                    type="number"
                    step="any"
                    value="<?php echo htmlspecialchars($numero2Texto); ?>"
                >

                <label for="operacion">Operacion</label>
                <select id="operacion" name="operacion">
                    <option value="sumar" <?php echo $operacion === 'sumar' ? 'selected' : ''; ?>>Sumar</option>
                    <option value="restar" <?php echo $operacion === 'restar' ? 'selected' : ''; ?>>Restar</option>
                    <option value="multiplicar" <?php echo $operacion === 'multiplicar' ? 'selected' : ''; ?>>Multiplicar</option>
                    <option value="redondear" <?php echo $operacion === 'redondear' ? 'selected' : ''; ?>>Redondear numero 1</option>
                </select>

                <label for="decimales">Decimales para redondear</label>
                <input
                    id="decimales"
                    name="decimales"
                    type="number"
                    min="0"
                    max="10"
                    value="<?php echo htmlspecialchars($decimalesTexto); ?>"
                >

                <button type="submit">Calcular</button>
            </form>

            <?php if ($error !== ''): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($resultado !== null && $error === ''): ?>
                <div class="result">
                    Resultado:
                    <strong><?php echo htmlspecialchars((string) $resultado); ?></strong>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
