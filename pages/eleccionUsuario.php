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
            <?php
                $usuario = $_GET['usuario'];
                echo "<div class='botonesGaleria'>";
                echo "<a href='galeria.php?usuario=" . $usuario . "&galeria=privada' class='galeria '>Ver galería privada</a>";

                echo "<a href='galeria.php?usuario=" . $usuario . "&galeria=publica' class='galeria '>Ver galería pública</a>";
                echo "</div>";
                echo "<a href='subirImagenes.php?usuario=" . $usuario . "' class='galeria '>Subir más imágenes</a>";
            ?>
        </div>        
    </body>
</html>