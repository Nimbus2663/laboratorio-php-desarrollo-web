<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comentarios en PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Comentarios</h1>
        <p>Ejemplos de comentarios de una linea y de varias lineas.</p>
    </header>

    <main>
        <p class="nav"><a href="index.php">Volver al indice</a></p>
        <section class="panel">
            <h2>Ejemplo</h2>
            <?php
            // Este comentario explica una sola linea.
            # Este tambien es un comentario de una linea.

            /*
             * Este comentario puede ocupar varias lineas.
             * Es util para documentar bloques de codigo.
             */
            echo '<p>Los comentarios no se muestran al usuario final.</p>';
            ?>
            <pre>// Comentario de una linea
# Comentario de una linea
/*
 Comentario de varias lineas
*/</pre>
        </section>
    </main>
</body>
</html>
