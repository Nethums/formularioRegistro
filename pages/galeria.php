<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/estilos.css">
        <title>Galería imágenes</title>
    </head>
    <body>        
        <div class="container ">
            <h1>Galería de imágenes del usuario</h1>
            <div class="galeria">
                <?php

                $usuario = $_GET['usuario'];
                $directorioUsuario = "../imagenes/" . $usuario . "/";
                $ficheros = scandir($directorioUsuario);
                /*Con array_slice nos quedamos solamente a partir del 3 valor del array (la segunda parte de los parámetros, en este caso el 2 ya que el primer valor es "." y el segundo "..")*/
                $fotosUsuarios = array_slice($ficheros, 2);
                
                foreach ($fotosUsuarios as $foto) {
                    echo "<a href=" . $directorioUsuario . $foto ." target='_blank'><img src=" . $directorioUsuario . $foto ."></a>";
                }
                


                ?>

            </div>              
        </div>        
    </body>
</html>