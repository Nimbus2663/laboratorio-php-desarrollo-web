<?php
$examples = [
    ['file' => 'phpinfo.php', 'title' => 'Verificacion de instalacion', 'desc' => 'Muestra la configuracion del servidor PHP.'],
    ['file' => 'hola_mundo.php', 'title' => 'Practica 1: Hola Mundo', 'desc' => 'Primer script PHP corregido.'],
    ['file' => 'php_embebido.php', 'title' => 'PHP embebido en HTML', 'desc' => 'Mezcla de HTML y codigo PHP.'],
    ['file' => 'variables.php', 'title' => 'Variables', 'desc' => 'Uso de variables en una pagina dinamica.'],
    ['file' => 'tipos_casting_constantes.php', 'title' => 'Tipos, casting y constantes', 'desc' => 'Ejemplos de gettype, settype, var_dump y define.'],
    ['file' => 'salida_formato.php', 'title' => 'Salida con formato', 'desc' => 'Uso correcto de printf y number_format.'],
    ['file' => 'operaciones_matematicas.php', 'title' => 'Operadores matematicos', 'desc' => 'Operaciones y precedencia de operadores.'],
    ['file' => 'comentarios.php', 'title' => 'Comentarios', 'desc' => 'Comentarios de una linea y varias lineas.'],
    ['file' => 'formulario_request.php', 'title' => 'Formulario y $_REQUEST', 'desc' => 'Captura de datos enviados por formulario.'],
    ['file' => 'conversor_pulgadas.php', 'title' => 'Laboratorio 1: Pulgadas a centimetros', 'desc' => 'Convierte pulgadas usando 1 pulgada = 2.54 cm.'],
    ['file' => 'calculadora.php', 'title' => 'Laboratorio 2: Calculadora', 'desc' => 'Suma, resta, multiplicacion y redondeo de decimales.'],
];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laboratorio PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Introduccion al lenguaje de programacion PHP</h1>
        <p>Ejemplos practicos y laboratorios solicitados en la presentacion.</p>
    </header>

    <main>
        <section class="grid">
            <?php foreach ($examples as $example): ?>
                <article class="card">
                    <h2><?php echo htmlspecialchars($example['title']); ?></h2>
                    <p><?php echo htmlspecialchars($example['desc']); ?></p>
                    <a href="<?php echo htmlspecialchars($example['file']); ?>">Abrir ejemplo</a>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
