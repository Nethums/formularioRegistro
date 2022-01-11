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
           
            <?php
                $usuario = $_GET['usuario'];
                




                echo "<h1>Imágenes del usuario " . $usuario . "</h1>";
                echo "<div class='galeria'>";
                $usuario = $_GET['usuario'];
                $directorioUsuario = "../imagenes/" . $usuario . "/";
                $galeria = $_GET['galeria'];

                if ($galeria == "privada") {
                    echo "<h2>Galería privada</h2>";
                
                    $ficheros = scandir($directorioUsuario);
                    /*Con array_slice nos quedamos solamente a partir del 3 valor del array (la segunda parte de los parámetros, en este caso el 2 ya que el primer valor es "." y el segundo "..")*/
                    $fotosUsuarios = array_slice($ficheros, 2);
                    
                    foreach ($fotosUsuarios as $foto) {
                        echo "<a href=" . $directorioUsuario . $foto ." target='_blank'><img src=" . $directorioUsuario . $foto ."></a>";
                    }

                    //Añadimos un botón por si el usuario quiere subir más imágenes
                    echo "<div class='botonesGaleria'>";
                    echo "<a href='subirImagenes.php?usuario=" . $usuario . "' class='galeria '>Subir más imágenes</a>";

                    echo "<a href='galeria.php?usuario=" . $usuario . "&galeria=publica' class='galeria '>Ver galería pública</a>";
                    echo "</div>";

                }

                if ($galeria == "publica") {
                    echo "<h2>Galería pública</h2>";
                    $usuario = $_GET['usuario'];
                    
                    try {
                        include ('../libs/bConecta.php');

                        /* Necesitamos saber la Id del usuario. Para ello hacemos la siguiente consulta */
                        $consultaId = "SELECT id_user  
                                        FROM usuarios 
                                        WHERE user=:usuario";
                        $result = $pdo->prepare($consultaId);
                        $result->execute(array(":usuario" => $usuario));                    
                        $idUsuario = $result->fetchColumn();  
                                                
                        /* Una vez sabemos la id del usuario podemos buscar todas las imágenes dentro de la tabla imágenes que correspondan a ese usuario buscando la ID y guardar los resultados en un array */
                        $consulta = "SELECT rutaImagen  
                                        FROM imagenes 
                                        WHERE idUser=:usuario";
                        $result = $pdo->prepare($consulta);
                        $result->execute(array(":usuario" => $idUsuario));                    
                        $imagenesPublicasUsuario = $result->fetchAll();  
                    }   catch (PDOException $e) {
                        // En este caso guardamos los errores en un archivo de errores log
                        error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                    }   

                    /* Queremos quedarnos solamente con las rutas de las imagenes que no contentan el nombre del usuario ya que esas rutas pertenecen a las fotos privadas. Para ello nos quedamos con la primera key del array interno y usamos una expresión regular \b se usa para buscar una palabra dentro de un string */
                    foreach ($imagenesPublicasUsuario as $arrayImagenes) {
                        foreach ($arrayImagenes as $rutaImagen => $ruta) {
                            if ($rutaImagen === array_key_first($arrayImagenes) && !preg_match("/\b$usuario\b/i", $ruta)) {
                                echo "<img src='../$ruta'></img><br>";
                            }                                
                        }
                    }            

                    //Añadimos un botón por si el usuario quiere subir más imágenes
                    echo "<div class='botonesGaleria'>";
                    echo "<a href='subirImagenes.php?usuario=" . $usuario . "' class='galeria '>Subir más imágenes</a>";

                    echo "<a href='galeria.php?usuario=" . $usuario . "&galeria=privada' class='galeria '>Ver galería privada</a>";
                    echo "</div>";
                }
                echo "</div>"; //Cerramos div class galeria
            ?>        
        </div>        
    </body>
</html>