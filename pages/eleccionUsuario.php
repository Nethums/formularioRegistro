<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/estilos.css">
        <title>¿Qué quieres hacer?</title>
    </head>
    <body>       
        <div class="container">
            <h1 class="registro">¿Qué quieres hacer?</h1>
            <ul class="eleccionUsuario">
                <?php
                    $usuario = $_GET['usuario'];
                    echo "<li><a href='galeria.php?usuario=" . $usuario . "'>Ver galería de fotos</a></li>";
                    echo "<li><a href='subirImagenes.php?usuario=" . $usuario . "'>Subir imágenes</a></li>";
                ?>
            </ul>
        </div>        
    </body>
</html>