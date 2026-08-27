<?php
function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$formAction = $_POST['form_action'] ?? '';

$converterInput = trim($_POST['converter_inches'] ?? '');
$converterResult = null;
$converterError = '';

$calcNumber1 = trim($_POST['calc_number1'] ?? '');
$calcNumber2 = trim($_POST['calc_number2'] ?? '');
$calcOperation = $_POST['calc_operation'] ?? 'sumar';
$calcDecimals = trim($_POST['calc_decimals'] ?? '2');
$calcResult = null;
$calcError = '';

$requestName = trim($_REQUEST['request_name'] ?? '');
$requestEmail = trim($_REQUEST['request_email'] ?? '');
$requestMessage = trim($_REQUEST['request_message'] ?? '');
$requestSent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formAction === 'converter') {
    if ($converterInput === '' || !is_numeric($converterInput)) {
        $converterError = 'Ingrese una cantidad valida de pulgadas.';
    } else {
        $inches = (float) $converterInput;
        $centimeters = $inches * 2.54;
        $converterResult = [
            'inches' => $inches,
            'centimeters' => $centimeters,
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formAction === 'calculator') {
    $needsSecondNumber = $calcOperation !== 'redondear';

    if ($calcNumber1 === '' || !is_numeric($calcNumber1)) {
        $calcError = 'Ingrese un primer numero valido.';
    } elseif ($needsSecondNumber && ($calcNumber2 === '' || !is_numeric($calcNumber2))) {
        $calcError = 'Ingrese un segundo numero valido.';
    } elseif ($calcDecimals === '' || !ctype_digit($calcDecimals)) {
        $calcError = 'Ingrese una cantidad valida de decimales.';
    } else {
        $number1 = (float) $calcNumber1;
        $number2 = (float) $calcNumber2;
        $decimals = (int) $calcDecimals;

        switch ($calcOperation) {
            case 'sumar':
                $calcResult = $number1 + $number2;
                break;
            case 'restar':
                $calcResult = $number1 - $number2;
                break;
            case 'multiplicar':
                $calcResult = $number1 * $number2;
                break;
            case 'redondear':
                $calcResult = round($number1, $decimals);
                break;
            default:
                $calcError = 'Seleccione una operacion valida.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formAction === 'request') {
    $requestSent = $requestName !== '' || $requestEmail !== '' || $requestMessage !== '';
}

$author = 'Ricardo Caballero';
$course = 'Desarrollo Web';
$integerValue = 25;
$floatValue = 3.75;
$textValue = '42';
$booleanValue = true;
$castToInt = (int) $floatValue;
$castToFloat = (float) $textValue;
$settypeExample = '100';
settype($settypeExample, 'integer');
define('INCH_TO_CM', 2.54);

ob_start();
var_dump($floatValue, $booleanValue);
$dumpOutput = trim(ob_get_clean());

$mathA = 20;
$mathB = 6;
$mathExamples = [
    'Suma' => $mathA + $mathB,
    'Resta' => $mathA - $mathB,
    'Multiplicacion' => $mathA * $mathB,
    'Division' => $mathA / $mathB,
    'Modulo' => $mathA % $mathB,
    'Exponenciacion' => $mathA ** 2,
];

$supportFiles = [
    ['file' => 'phpinfo.php', 'title' => 'Verificacion de PHP'],
    ['file' => 'hola_mundo.php', 'title' => 'Hola Mundo'],
    ['file' => 'php_embebido.php', 'title' => 'PHP embebido'],
    ['file' => 'variables.php', 'title' => 'Variables'],
    ['file' => 'tipos_casting_constantes.php', 'title' => 'Tipos y casting'],
    ['file' => 'salida_formato.php', 'title' => 'Salida con formato'],
    ['file' => 'operaciones_matematicas.php', 'title' => 'Operadores'],
    ['file' => 'comentarios.php', 'title' => 'Comentarios'],
    ['file' => 'formulario_request.php', 'title' => 'Formulario $_REQUEST'],
    ['file' => 'conversor_pulgadas.php', 'title' => 'Conversor separado'],
    ['file' => 'calculadora.php', 'title' => 'Calculadora separada'],
];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laboratorio PHP - Desarrollo Web</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="hero">
        <div>
            <p class="eyebrow">Desarrollo Web</p>
            <h1>Laboratorio PHP</h1>
            <p>
                Pagina principal con practicas funcionales de PHP: formularios,
                operaciones, conversiones, variables, tipos de datos y salida en pantalla.
            </p>
        </div>
        <div class="student-box">
            <span>Estudiante</span>
            <strong><?php echo h($author); ?></strong>
            <span>Repositorio publico</span>
            <a href="https://github.com/Nimbus2663/laboratorio-php-desarrollo-web" target="_blank" rel="noopener">
                GitHub
            </a>
        </div>
    </header>

    <main>
        <section class="section-heading">
            <p class="eyebrow">Parte principal</p>
            <h2>Herramientas funcionando dentro de esta pagina</h2>
        </section>

        <section class="tool-grid">
            <article class="panel" id="conversor">
                <h2>Conversor de pulgadas a centimetros</h2>
                <p class="muted">Formula: centimetros = pulgadas x 2.54</p>

                <form method="post" action="#conversor">
                    <input type="hidden" name="form_action" value="converter">

                    <label for="converter_inches">Pulgadas</label>
                    <input
                        id="converter_inches"
                        name="converter_inches"
                        type="number"
                        step="0.01"
                        min="0"
                        value="<?php echo h($converterInput); ?>"
                        required
                    >

                    <button type="submit">Convertir</button>
                </form>

                <?php if ($converterError !== ''): ?>
                    <div class="error"><?php echo h($converterError); ?></div>
                <?php endif; ?>

                <?php if ($converterResult !== null): ?>
                    <div class="result">
                        <strong><?php echo number_format($converterResult['inches'], 2); ?> pulgadas</strong>
                        equivalen a
                        <strong><?php echo number_format($converterResult['centimeters'], 2); ?> centimetros</strong>.
                    </div>
                <?php endif; ?>
            </article>

            <article class="panel" id="calculadora">
                <h2>Calculadora en PHP</h2>
                <p class="muted">Suma, resta, multiplicacion y redondeo de decimales.</p>

                <form method="post" action="#calculadora">
                    <input type="hidden" name="form_action" value="calculator">

                    <label for="calc_number1">Numero 1</label>
                    <input
                        id="calc_number1"
                        name="calc_number1"
                        type="number"
                        step="any"
                        value="<?php echo h($calcNumber1); ?>"
                        required
                    >

                    <label for="calc_number2">Numero 2</label>
                    <input
                        id="calc_number2"
                        name="calc_number2"
                        type="number"
                        step="any"
                        value="<?php echo h($calcNumber2); ?>"
                    >

                    <label for="calc_operation">Operacion</label>
                    <select id="calc_operation" name="calc_operation">
                        <option value="sumar" <?php echo $calcOperation === 'sumar' ? 'selected' : ''; ?>>Sumar</option>
                        <option value="restar" <?php echo $calcOperation === 'restar' ? 'selected' : ''; ?>>Restar</option>
                        <option value="multiplicar" <?php echo $calcOperation === 'multiplicar' ? 'selected' : ''; ?>>Multiplicar</option>
                        <option value="redondear" <?php echo $calcOperation === 'redondear' ? 'selected' : ''; ?>>Redondear numero 1</option>
                    </select>

                    <label for="calc_decimals">Decimales para redondear</label>
                    <input
                        id="calc_decimals"
                        name="calc_decimals"
                        type="number"
                        min="0"
                        max="10"
                        value="<?php echo h($calcDecimals); ?>"
                    >

                    <button type="submit">Calcular</button>
                </form>

                <?php if ($calcError !== ''): ?>
                    <div class="error"><?php echo h($calcError); ?></div>
                <?php endif; ?>

                <?php if ($calcResult !== null && $calcError === ''): ?>
                    <div class="result">
                        Resultado: <strong><?php echo h($calcResult); ?></strong>
                    </div>
                <?php endif; ?>
            </article>
        </section>

        <section class="tool-grid">
            <article class="panel" id="request">
                <h2>Formulario con $_REQUEST</h2>
                <p class="muted">El mismo archivo recibe y muestra los datos enviados.</p>

                <form method="post" action="#request">
                    <input type="hidden" name="form_action" value="request">

                    <label for="request_name">Nombre</label>
                    <input id="request_name" name="request_name" value="<?php echo h($requestName); ?>">

                    <label for="request_email">Correo</label>
                    <input id="request_email" name="request_email" type="email" value="<?php echo h($requestEmail); ?>">

                    <label for="request_message">Mensaje</label>
                    <input id="request_message" name="request_message" value="<?php echo h($requestMessage); ?>">

                    <button type="submit">Enviar datos</button>
                </form>

                <?php if ($requestSent): ?>
                    <div class="result">
                        <p><strong>Nombre:</strong> <?php echo h($requestName); ?></p>
                        <p><strong>Correo:</strong> <?php echo h($requestEmail); ?></p>
                        <p><strong>Mensaje:</strong> <?php echo h($requestMessage); ?></p>
                    </div>
                <?php endif; ?>
            </article>

            <article class="panel">
                <h2>Variables y salida dinamica</h2>
                <p>
                    Esta pagina fue preparada por
                    <strong><?php echo h($author); ?></strong>
                    para la materia
                    <strong><?php echo h($course); ?></strong>.
                </p>
                <p>
                    Fecha generada por PHP:
                    <strong><?php echo h(date('d/m/Y H:i')); ?></strong>
                </p>
                <pre>echo "Hola desde PHP";</pre>
            </article>
        </section>

        <section class="panel">
            <h2>Tipos de datos, casting y constantes</h2>
            <table>
                <tr>
                    <th>Concepto</th>
                    <th>Resultado en PHP</th>
                </tr>
                <tr>
                    <td>gettype($integerValue)</td>
                    <td><?php echo h(gettype($integerValue)); ?></td>
                </tr>
                <tr>
                    <td>gettype($floatValue)</td>
                    <td><?php echo h(gettype($floatValue)); ?></td>
                </tr>
                <tr>
                    <td>(int) 3.75</td>
                    <td><?php echo h($castToInt); ?></td>
                </tr>
                <tr>
                    <td>(float) "42"</td>
                    <td><?php echo h($castToFloat); ?></td>
                </tr>
                <tr>
                    <td>settype("100", "integer")</td>
                    <td><?php echo h($settypeExample . ' - ' . gettype($settypeExample)); ?></td>
                </tr>
                <tr>
                    <td>Constante INCH_TO_CM</td>
                    <td><?php echo h(INCH_TO_CM); ?></td>
                </tr>
            </table>

            <h2>var_dump</h2>
            <pre><?php echo h($dumpOutput); ?></pre>
        </section>

        <section class="panel">
            <h2>Operadores matematicos y precedencia</h2>
            <p class="muted">Valores usados: $mathA = <?php echo h($mathA); ?> y $mathB = <?php echo h($mathB); ?></p>

            <table>
                <tr>
                    <th>Operacion</th>
                    <th>Resultado</th>
                </tr>
                <?php foreach ($mathExamples as $name => $value): ?>
                    <tr>
                        <td><?php echo h($name); ?></td>
                        <td><?php echo h(round($value, 4)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="result">
                <p>2 + 3 * 4 = <strong><?php echo h(2 + 3 * 4); ?></strong></p>
                <p>(2 + 3) * 4 = <strong><?php echo h((2 + 3) * 4); ?></strong></p>
            </div>
        </section>

        <section class="panel">
            <h2>Archivos de apoyo</h2>
            <p class="muted">
                Estos enlaces abren las practicas separadas. La funcionalidad principal ya esta integrada arriba.
            </p>

            <div class="link-grid">
                <?php foreach ($supportFiles as $supportFile): ?>
                    <a href="<?php echo h($supportFile['file']); ?>"><?php echo h($supportFile['title']); ?></a>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
</html>
