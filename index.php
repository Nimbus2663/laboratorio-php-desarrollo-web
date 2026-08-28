<?php
session_start();

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function parse_number($value)
{
    $normalized = str_replace([' ', ','], ['', '.'], trim((string) $value));

    if ($normalized === '' || !is_numeric($normalized)) {
        return null;
    }

    return (float) $normalized;
}

function fixed_number($value, $decimals = 2)
{
    return number_format((float) $value, $decimals, '.', ',');
}

function clean_number($value, $decimals = 4)
{
    $formatted = number_format((float) $value, $decimals, '.', ',');
    return rtrim(rtrim($formatted, '0'), '.');
}

function text_value($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if ($value === null) {
        return 'NULL';
    }

    return (string) $value;
}

function add_history($tool, $detail, $result)
{
    if (!isset($_SESSION['tool_history']) || !is_array($_SESSION['tool_history'])) {
        $_SESSION['tool_history'] = [];
    }

    array_unshift($_SESSION['tool_history'], [
        'tool' => $tool,
        'detail' => $detail,
        'result' => $result,
        'time' => date('H:i:s'),
    ]);

    $_SESSION['tool_history'] = array_slice($_SESSION['tool_history'], 0, 8);
}

function convert_length($value, $fromUnit, $toUnit, $unitOptions)
{
    if (!isset($unitOptions[$fromUnit], $unitOptions[$toUnit])) {
        return null;
    }

    $meters = $value * $unitOptions[$fromUnit]['to_meter'];
    return $meters / $unitOptions[$toUnit]['to_meter'];
}

if (!defined('INCH_TO_CM')) {
    define('INCH_TO_CM', 2.54);
}

if (!defined('FOOT_TO_METER')) {
    define('FOOT_TO_METER', 0.3048);
}

if (!defined('MILE_TO_KM')) {
    define('MILE_TO_KM', 1.60934);
}

$author = 'Ricardo Caballero';
$course = 'Desarrollo Web';
$formAction = $_POST['form_action'] ?? '';
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formAction === 'clear_history') {
    $_SESSION['tool_history'] = [];
    $notice = 'Historial limpiado correctamente.';
}

$unitOptions = [
    'mm' => [
        'label' => 'Milimetros',
        'symbol' => 'mm',
        'to_meter' => 0.001,
    ],
    'cm' => [
        'label' => 'Centimetros',
        'symbol' => 'cm',
        'to_meter' => 0.01,
    ],
    'm' => [
        'label' => 'Metros',
        'symbol' => 'm',
        'to_meter' => 1,
    ],
    'km' => [
        'label' => 'Kilometros',
        'symbol' => 'km',
        'to_meter' => 1000,
    ],
    'in' => [
        'label' => 'Pulgadas',
        'symbol' => 'in',
        'to_meter' => INCH_TO_CM / 100,
    ],
    'ft' => [
        'label' => 'Pies',
        'symbol' => 'ft',
        'to_meter' => FOOT_TO_METER,
    ],
    'yd' => [
        'label' => 'Yardas',
        'symbol' => 'yd',
        'to_meter' => 0.9144,
    ],
    'mi' => [
        'label' => 'Millas',
        'symbol' => 'mi',
        'to_meter' => MILE_TO_KM * 1000,
    ],
];

$unitValueRaw = trim($_POST['unit_value'] ?? '10');
$unitFrom = $_POST['unit_from'] ?? 'in';
$unitTo = $_POST['unit_to'] ?? 'cm';
$unitResult = null;
$unitError = '';

if (!isset($unitOptions[$unitFrom])) {
    $unitFrom = 'in';
}

if (!isset($unitOptions[$unitTo])) {
    $unitTo = 'cm';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formAction === 'unit_converter') {
    $unitValue = parse_number($unitValueRaw);

    if ($unitValue === null) {
        $unitError = 'Ingrese un numero valido para convertir.';
    } else {
        $convertedValue = convert_length($unitValue, $unitFrom, $unitTo, $unitOptions);
        $conversionRate = convert_length(1, $unitFrom, $unitTo, $unitOptions);
        $unitResult = [
            'input' => $unitValue,
            'output' => $convertedValue,
            'from_meta' => $unitOptions[$unitFrom],
            'to_meta' => $unitOptions[$unitTo],
            'rate' => $conversionRate,
        ];

        add_history(
            'Conversor',
            fixed_number($unitValue) . ' ' . $unitOptions[$unitFrom]['symbol'] . ' a ' . $unitOptions[$unitTo]['symbol'],
            fixed_number($convertedValue) . ' ' . $unitOptions[$unitTo]['symbol']
        );
    }
}

$calcOptions = [
    'sumar' => 'Sumar',
    'restar' => 'Restar',
    'multiplicar' => 'Multiplicar',
    'dividir' => 'Dividir',
    'potencia' => 'Potencia',
    'modulo' => 'Modulo',
    'porcentaje' => 'Porcentaje de',
    'promedio' => 'Promedio',
    'redondear' => 'Redondear numero 1',
];

$calcNumber1Raw = trim($_POST['calc_number1'] ?? '12.5');
$calcNumber2Raw = trim($_POST['calc_number2'] ?? '3');
$calcOperation = $_POST['calc_operation'] ?? 'sumar';
$calcDecimalsRaw = trim($_POST['calc_decimals'] ?? '2');
$calcResult = null;
$calcError = '';

if (!isset($calcOptions[$calcOperation])) {
    $calcOperation = 'sumar';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formAction === 'calculator') {
    $number1 = parse_number($calcNumber1Raw);
    $number2 = parse_number($calcNumber2Raw);
    $needsSecondNumber = $calcOperation !== 'redondear';

    if ($calcDecimalsRaw === '' || !ctype_digit($calcDecimalsRaw)) {
        $calcError = 'Ingrese una cantidad valida de decimales.';
    } elseif ((int) $calcDecimalsRaw > 8) {
        $calcError = 'Use 8 decimales o menos para mantener una salida legible.';
    } elseif ($number1 === null) {
        $calcError = 'Ingrese un primer numero valido.';
    } elseif ($needsSecondNumber && $number2 === null) {
        $calcError = 'Ingrese un segundo numero valido.';
    } else {
        $decimals = (int) $calcDecimalsRaw;

        switch ($calcOperation) {
            case 'sumar':
                $resultValue = $number1 + $number2;
                $expression = clean_number($number1) . ' + ' . clean_number($number2);
                break;
            case 'restar':
                $resultValue = $number1 - $number2;
                $expression = clean_number($number1) . ' - ' . clean_number($number2);
                break;
            case 'multiplicar':
                $resultValue = $number1 * $number2;
                $expression = clean_number($number1) . ' x ' . clean_number($number2);
                break;
            case 'dividir':
                if (abs($number2) < 0.000000001) {
                    $calcError = 'No se puede dividir entre cero.';
                    break;
                }

                $resultValue = $number1 / $number2;
                $expression = clean_number($number1) . ' / ' . clean_number($number2);
                break;
            case 'potencia':
                $resultValue = $number1 ** $number2;
                $expression = clean_number($number1) . ' ^ ' . clean_number($number2);
                break;
            case 'modulo':
                if (abs($number2) < 0.000000001) {
                    $calcError = 'No se puede calcular modulo con cero.';
                    break;
                }

                $resultValue = fmod($number1, $number2);
                $expression = clean_number($number1) . ' mod ' . clean_number($number2);
                break;
            case 'porcentaje':
                $resultValue = ($number1 / 100) * $number2;
                $expression = clean_number($number1) . '% de ' . clean_number($number2);
                break;
            case 'promedio':
                $resultValue = ($number1 + $number2) / 2;
                $expression = 'promedio de ' . clean_number($number1) . ' y ' . clean_number($number2);
                break;
            case 'redondear':
                $resultValue = round($number1, $decimals);
                $expression = 'round(' . clean_number($number1, 8) . ', ' . $decimals . ')';
                break;
            default:
                $calcError = 'Seleccione una operacion valida.';
                break;
        }

        if ($calcError === '') {
            $calcResult = [
                'value' => $resultValue,
                'expression' => $expression,
                'decimals' => $decimals,
            ];

            add_history(
                'Calculadora',
                $calcOptions[$calcOperation] . ': ' . $expression,
                clean_number($resultValue, max(2, $decimals))
            );
        }
    }
}

$typeTargets = [
    'integer' => 'Entero',
    'float' => 'Decimal',
    'string' => 'Cadena',
    'boolean' => 'Booleano',
];

$typeValueRaw = trim($_POST['type_value'] ?? '42.75');
$typeTarget = $_POST['type_target'] ?? 'integer';
$typeResult = null;

if (!isset($typeTargets[$typeTarget])) {
    $typeTarget = 'integer';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formAction === 'type_analyzer') {
    $settypeValue = $typeValueRaw;
    settype($settypeValue, $typeTarget);

    ob_start();
    var_dump($settypeValue);
    $dumpOutput = trim(ob_get_clean());

    $typeResult = [
        'raw' => $typeValueRaw,
        'raw_type' => gettype($typeValueRaw),
        'numeric' => parse_number($typeValueRaw) !== null ? 'si' : 'no',
        'cast_int' => (int) $typeValueRaw,
        'cast_float' => (float) str_replace(',', '.', $typeValueRaw),
        'set_value' => text_value($settypeValue),
        'set_type' => gettype($settypeValue),
        'dump' => $dumpOutput,
    ];

    add_history(
        'Tipos',
        'settype a ' . $typeTargets[$typeTarget],
        $typeResult['set_value'] . ' (' . $typeResult['set_type'] . ')'
    );
}

$requestName = trim($_REQUEST['request_name'] ?? '');
$requestEmail = trim($_REQUEST['request_email'] ?? '');
$requestMessage = trim($_REQUEST['request_message'] ?? '');
$requestSent = false;
$requestError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formAction === 'request') {
    if ($requestName === '' && $requestEmail === '' && $requestMessage === '') {
        $requestError = 'Complete al menos un campo del formulario.';
    } else {
        $requestSent = true;

        add_history(
            'Formulario',
            'Datos recibidos con $_REQUEST',
            $requestName !== '' ? $requestName : 'Registro recibido'
        );
    }
}

$integerValue = 25;
$floatValue = 3.75;
$textValue = '42';
$booleanValue = true;
$settypeExample = '100';
settype($settypeExample, 'integer');

ob_start();
var_dump($floatValue, $booleanValue);
$demoDumpOutput = trim(ob_get_clean());

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

$history = $_SESSION['tool_history'] ?? [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel PHP - Desarrollo Web</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <span class="brand-mark">PHP</span>
            <div>
                <p class="eyebrow">Desarrollo Web</p>
                <h1>Panel de herramientas PHP</h1>
            </div>
        </div>

        <div class="identity">
            <span>Estudiante</span>
            <strong><?php echo h($author); ?></strong>
            <a href="https://github.com/Nimbus2663/laboratorio-php-desarrollo-web" target="_blank" rel="noopener">
                Repositorio GitHub
            </a>
        </div>
    </header>

    <nav class="workspace-nav" aria-label="Herramientas principales">
        <a href="#calculadora">Calculadora</a>
        <a href="#conversor">Conversor</a>
        <a href="#tipos">Tipos PHP</a>
        <a href="#request">Formulario</a>
        <a href="#historial">Historial</a>
    </nav>

    <main class="app-shell">
        <?php if ($notice !== ''): ?>
            <div class="notice"><?php echo h($notice); ?></div>
        <?php endif; ?>

        <section class="stats-row" aria-label="Estado del laboratorio">
            <article class="metric">
                <span>PHP activo</span>
                <strong><?php echo h(PHP_VERSION); ?></strong>
            </article>
            <article class="metric">
                <span>Herramientas</span>
                <strong>4</strong>
            </article>
            <article class="metric">
                <span>Fecha del servidor</span>
                <strong><?php echo h(date('d/m/Y H:i')); ?></strong>
            </article>
            <article class="metric">
                <span>Materia</span>
                <strong><?php echo h($course); ?></strong>
            </article>
        </section>

        <section class="primary-grid">
            <article class="tool-card featured" id="calculadora">
                <div class="tool-card-header">
                    <div>
                        <p class="eyebrow">Herramienta principal</p>
                        <h2>Calculadora avanzada</h2>
                    </div>
                    <span class="badge">PHP</span>
                </div>

                <form method="post" action="#calculadora" class="tool-form">
                    <input type="hidden" name="form_action" value="calculator">

                    <div class="field-row">
                        <div class="field-group">
                            <label for="calc_number1">Numero 1</label>
                            <input id="calc_number1" name="calc_number1" value="<?php echo h($calcNumber1Raw); ?>" inputmode="decimal" required>
                        </div>

                        <div class="field-group">
                            <label for="calc_number2">Numero 2</label>
                            <input id="calc_number2" name="calc_number2" value="<?php echo h($calcNumber2Raw); ?>" inputmode="decimal">
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label for="calc_operation">Operacion</label>
                            <select id="calc_operation" name="calc_operation">
                                <?php foreach ($calcOptions as $value => $label): ?>
                                    <option value="<?php echo h($value); ?>" <?php echo $calcOperation === $value ? 'selected' : ''; ?>>
                                        <?php echo h($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="field-group">
                            <label for="calc_decimals">Decimales</label>
                            <input id="calc_decimals" name="calc_decimals" type="number" min="0" max="8" value="<?php echo h($calcDecimalsRaw); ?>">
                        </div>
                    </div>

                    <button type="submit">Calcular</button>
                </form>

                <?php if ($calcError !== ''): ?>
                    <div class="error"><?php echo h($calcError); ?></div>
                <?php endif; ?>

                <?php if ($calcResult !== null): ?>
                    <div class="result result-strong">
                        <span>Resultado</span>
                        <strong><?php echo h(clean_number($calcResult['value'], max(2, $calcResult['decimals']))); ?></strong>
                        <small><?php echo h($calcResult['expression']); ?></small>
                    </div>
                <?php endif; ?>
            </article>

            <article class="tool-card" id="conversor">
                <div class="tool-card-header">
                    <div>
                        <p class="eyebrow">Unidades</p>
                        <h2>Conversor</h2>
                    </div>
                    <span class="badge accent">Libre</span>
                </div>

                <form method="post" action="#conversor" class="tool-form">
                    <input type="hidden" name="form_action" value="unit_converter">

                    <label for="unit_value">Valor</label>
                    <input id="unit_value" name="unit_value" value="<?php echo h($unitValueRaw); ?>" inputmode="decimal" required>

                    <div class="field-row">
                        <div class="field-group">
                            <label for="unit_from">Convertir desde</label>
                            <select id="unit_from" name="unit_from">
                                <?php foreach ($unitOptions as $value => $option): ?>
                                    <option value="<?php echo h($value); ?>" <?php echo $unitFrom === $value ? 'selected' : ''; ?>>
                                        <?php echo h($option['label']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="field-group">
                            <label for="unit_to">Convertir a</label>
                            <select id="unit_to" name="unit_to">
                                <?php foreach ($unitOptions as $value => $option): ?>
                                    <option value="<?php echo h($value); ?>" <?php echo $unitTo === $value ? 'selected' : ''; ?>>
                                        <?php echo h($option['label']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button type="submit">Convertir</button>
                </form>

                <?php if ($unitError !== ''): ?>
                    <div class="error"><?php echo h($unitError); ?></div>
                <?php endif; ?>

                <?php if ($unitResult !== null): ?>
                    <div class="result result-strong">
                        <span>
                            1 <?php echo h($unitResult['from_meta']['symbol']); ?>
                            =
                            <?php echo h(clean_number($unitResult['rate'], 8)); ?>
                            <?php echo h($unitResult['to_meta']['symbol']); ?>
                        </span>
                        <strong>
                            <?php echo h(fixed_number($unitResult['output'])); ?>
                            <?php echo h($unitResult['to_meta']['symbol']); ?>
                        </strong>
                        <small>
                            <?php echo h(fixed_number($unitResult['input']) . ' ' . $unitResult['from_meta']['label']); ?>
                            a
                            <?php echo h($unitResult['to_meta']['label']); ?>
                        </small>
                    </div>
                <?php endif; ?>
            </article>
        </section>

        <section class="secondary-grid">
            <article class="tool-card" id="tipos">
                <div class="tool-card-header">
                    <div>
                        <p class="eyebrow">Fundamentos</p>
                        <h2>Analizador de tipos</h2>
                    </div>
                    <span class="badge muted-badge">settype</span>
                </div>

                <form method="post" action="#tipos" class="tool-form">
                    <input type="hidden" name="form_action" value="type_analyzer">

                    <label for="type_value">Valor a analizar</label>
                    <input id="type_value" name="type_value" value="<?php echo h($typeValueRaw); ?>">

                    <label for="type_target">Convertir con settype()</label>
                    <select id="type_target" name="type_target">
                        <?php foreach ($typeTargets as $value => $label): ?>
                            <option value="<?php echo h($value); ?>" <?php echo $typeTarget === $value ? 'selected' : ''; ?>>
                                <?php echo h($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">Analizar</button>
                </form>

                <?php if ($typeResult !== null): ?>
                    <table class="data-table compact-table">
                        <tr>
                            <th>Prueba</th>
                            <th>Salida</th>
                        </tr>
                        <tr>
                            <td>Valor recibido</td>
                            <td><?php echo h($typeResult['raw']); ?></td>
                        </tr>
                        <tr>
                            <td>gettype inicial</td>
                            <td><?php echo h($typeResult['raw_type']); ?></td>
                        </tr>
                        <tr>
                            <td>is_numeric()</td>
                            <td><?php echo h($typeResult['numeric']); ?></td>
                        </tr>
                        <tr>
                            <td>(int)</td>
                            <td><?php echo h($typeResult['cast_int']); ?></td>
                        </tr>
                        <tr>
                            <td>(float)</td>
                            <td><?php echo h($typeResult['cast_float']); ?></td>
                        </tr>
                        <tr>
                            <td>settype()</td>
                            <td><?php echo h($typeResult['set_value'] . ' (' . $typeResult['set_type'] . ')'); ?></td>
                        </tr>
                    </table>

                    <pre class="code-block"><?php echo h($typeResult['dump']); ?></pre>
                <?php endif; ?>
            </article>

            <article class="tool-card" id="request">
                <div class="tool-card-header">
                    <div>
                        <p class="eyebrow">Formulario</p>
                        <h2>Prueba con $_REQUEST</h2>
                    </div>
                    <span class="badge muted-badge">POST</span>
                </div>

                <form method="post" action="#request" class="tool-form">
                    <input type="hidden" name="form_action" value="request">

                    <label for="request_name">Nombre</label>
                    <input id="request_name" name="request_name" value="<?php echo h($requestName); ?>">

                    <label for="request_email">Correo</label>
                    <input id="request_email" name="request_email" type="email" value="<?php echo h($requestEmail); ?>">

                    <label for="request_message">Mensaje</label>
                    <textarea id="request_message" name="request_message" rows="4"><?php echo h($requestMessage); ?></textarea>

                    <button type="submit">Enviar datos</button>
                </form>

                <?php if ($requestError !== ''): ?>
                    <div class="error"><?php echo h($requestError); ?></div>
                <?php endif; ?>

                <?php if ($requestSent): ?>
                    <div class="result">
                        <p><strong>Nombre:</strong> <?php echo h($requestName); ?></p>
                        <p><strong>Correo:</strong> <?php echo h($requestEmail); ?></p>
                        <p><strong>Mensaje:</strong> <?php echo h($requestMessage); ?></p>
                        <p><strong>Metodo:</strong> <?php echo h($_SERVER['REQUEST_METHOD']); ?></p>
                    </div>
                <?php endif; ?>
            </article>
        </section>

        <section class="info-grid">
            <article class="tool-card">
                <div class="tool-card-header">
                    <div>
                        <p class="eyebrow">Variables</p>
                        <h2>Salida dinamica</h2>
                    </div>
                </div>

                <table class="data-table">
                    <tr>
                        <th>Variable</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                    </tr>
                    <tr>
                        <td>$author</td>
                        <td><?php echo h($author); ?></td>
                        <td><?php echo h(gettype($author)); ?></td>
                    </tr>
                    <tr>
                        <td>$integerValue</td>
                        <td><?php echo h($integerValue); ?></td>
                        <td><?php echo h(gettype($integerValue)); ?></td>
                    </tr>
                    <tr>
                        <td>$floatValue</td>
                        <td><?php echo h($floatValue); ?></td>
                        <td><?php echo h(gettype($floatValue)); ?></td>
                    </tr>
                    <tr>
                        <td>INCH_TO_CM</td>
                        <td><?php echo h(INCH_TO_CM); ?></td>
                        <td>constant</td>
                    </tr>
                </table>
            </article>

            <article class="tool-card">
                <div class="tool-card-header">
                    <div>
                        <p class="eyebrow">Operadores</p>
                        <h2>Resultados en pantalla</h2>
                    </div>
                </div>

                <p class="muted">Valores usados: $mathA = <?php echo h($mathA); ?> y $mathB = <?php echo h($mathB); ?></p>

                <table class="data-table">
                    <tr>
                        <th>Operacion</th>
                        <th>Resultado</th>
                    </tr>
                    <?php foreach ($mathExamples as $name => $value): ?>
                        <tr>
                            <td><?php echo h($name); ?></td>
                            <td><?php echo h(clean_number($value, 4)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <div class="result">
                    <p>2 + 3 * 4 = <strong><?php echo h(2 + 3 * 4); ?></strong></p>
                    <p>(2 + 3) * 4 = <strong><?php echo h((2 + 3) * 4); ?></strong></p>
                </div>
            </article>
        </section>

        <section class="tool-card full-width" id="historial">
            <div class="tool-card-header">
                <div>
                    <p class="eyebrow">Sesion actual</p>
                    <h2>Historial de resultados</h2>
                </div>

                <?php if (!empty($history)): ?>
                    <form method="post" action="#historial">
                        <input type="hidden" name="form_action" value="clear_history">
                        <button class="button-secondary" type="submit">Limpiar</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if (empty($history)): ?>
                <p class="empty-state">Aun no hay resultados registrados en esta sesion.</p>
            <?php else: ?>
                <div class="history-list">
                    <?php foreach ($history as $item): ?>
                        <article class="history-item">
                            <span><?php echo h($item['time']); ?></span>
                            <strong><?php echo h($item['tool']); ?></strong>
                            <p><?php echo h($item['detail']); ?></p>
                            <em><?php echo h($item['result']); ?></em>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="tool-card full-width">
            <div class="tool-card-header">
                <div>
                    <p class="eyebrow">Respaldo</p>
                    <h2>Practicas separadas</h2>
                </div>
            </div>

            <div class="link-grid">
                <?php foreach ($supportFiles as $supportFile): ?>
                    <a href="<?php echo h($supportFile['file']); ?>"><?php echo h($supportFile['title']); ?></a>
                <?php endforeach; ?>
            </div>
        </section>

        <footer class="page-footer">
            <span><?php echo h($author); ?> - <?php echo h($course); ?></span>
            <span><?php echo h(date('Y')); ?></span>
        </footer>
    </main>
</body>
</html>
