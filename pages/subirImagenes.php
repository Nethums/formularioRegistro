<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/estilos.css">
        <title>Subir imágenes</title>
    </head>
    <body>       
        <div class="container">
            <h1>Subir imágenes</h1>
            <?php 
                include("../forms/formularioSubirImagenes.php");
                //Recogemos el valor del GET y si el mensaje es ok entonces añadimos el echo
                if (isset($_GET['foto'])) {   
                    $message = $_GET['foto'];
                    if ($message == "ok") {
                         echo "<p class=" . 'fotoSubida' .">La foto se ha guardado en su carpeta.</p>";
                    }
                }   

                //Recogemos el valor del GET y si el mensaje es error entonces añadimos el echo
                if (isset($_GET['foto'])) {   
                    $message = $_GET['foto'];
                    if ($message == "error") {
                         echo "<p class=" . 'errorLogin' .">La foto no se ha podido guardar.</p>";
                    }
                }  

                //Añadimos un botón por si el usuario quiere subir más imágenes
                $usuario = $_GET['usuario'];
                echo "<div class='botonesSubirImagenes'>";
                echo "<a href='galeria.php?usuario=" . $usuario . "&galeria=privada' class='galeria'>Ver galería privada</a>";

                echo "<a href='galeria.php?usuario=" . $usuario . "&galeria=publica' class='galeria'>Ver galería pública</a>";
                echo "</div>";
            ?>
        </div>        
    </body>
</html>