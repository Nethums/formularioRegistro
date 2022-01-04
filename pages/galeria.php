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
        <h1>Imágenes del usuario</h1>
            <div class="galeria">
                <?php
                    echo "<h2>Galería privada</h2>";
                    $usuario = $_GET['usuario'];
                    $directorioUsuario = "../imagenes/" . $usuario . "/";
                    $ficheros = scandir($directorioUsuario);
                    /*Con array_slice nos quedamos solamente a partir del 3 valor del array (la segunda parte de los parámetros, en este caso el 2 ya que el primer valor es "." y el segundo "..")*/
                    $fotosUsuarios = array_slice($ficheros, 2);
                    
                    foreach ($fotosUsuarios as $foto) {
                        echo "<a href=" . $directorioUsuario . $foto ." target='_blank'><img src=" . $directorioUsuario . $foto ."></a>";
                    }

                    //Añadimos un botón por si el usuario quiere subir más imágenes
                    echo "<a href='subirImagenes.php?usuario=" . $usuario . "' class='galeria'>Subir más imágenes</a>";
                ?>
            </div>      
            <div class="galeria">
                <?php
                    echo "<h2>Galería pública</h2>";
                    $usuario = $_GET['usuario'];
                    
// SELECT rutaImagen FROM imagenes LEFT JOIN usuarios ON imagenes.idUser = usuarios.id_user WHERE usuarios.id_user = 100; 

                    try {
                        include ('../libs/bConecta.php');
                                                
                        $consulta = "SELECT rutaImagen  
                                     FROM imagenes 
                                     WHERE user=:usuario";
                        $result = $pdo->prepare($consulta);
                        $result->execute(array(":usuario" => $usuario));                    
                        $test = $result -> rowCount();
                        $idUsuario = $result->fetchColumn();  //Sólo nos interesa el valor de la id por eso utilizamos fetchColumn en lugar de fetch() o fetchAll()
                        print_r($idUsuario);
                        echo "<br>";
                        
                        // Preparamos consulta
                        $stmt = $pdo->prepare("INSERT INTO imagenes (rutaImagen, idUser, descripcion) values (?, ?, ?)");
                        /* Preparamos el nombre de la foto para quitarle todos los espacios antes de meterla en la base de datos */
                        $nombreFotoSinEspacios = reemplazarEnFiles ("fotoUsuario", "name", " ", "_");
                        /* Aseguramos que no hayan imágenes duplicadas, si la hay le cambiamos el nombre y añadimos time() */
                        $nombreImagenFinal = cambiarNombreFotoSiEstaEnDirectorio ($directorioUsuarios, $usuario, $nombreFotoSinEspacios);
                        $rutaImagenUsuario = $directorioUsuarios . $usuario . "/" . $nombreImagenFinal;
                        $descripcionFoto = $_REQUEST['descripcionFoto'];
                        
                        // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden
                        $stmt->bindParam(1, $rutaImagenUsuario);
                        $stmt->bindParam(2, $idUsuario);
                        $stmt->bindParam(3, $descripcionFoto);
                    
                        // Excecute - Ejecutamos la sentencia. Nos de vuelve true o false
                        if ($stmt->execute()) {                    
                            echo "Se ha subido la foto correctamente";     
                            $userIdGuardado = $pdo->lastInsertId(); //Guardamos el id del usuario    
                        } else {
                            echo "No se ha guardado la imagen";
                        }                  
                    } catch (PDOException $e) {
                        // En este caso guardamos los errores en un archivo de errores log
                        error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                        // guardamos en ·errores el error que queremos mostrar a los usuarios
                        $errores['datos'] = "Ha habido un error <br>";
                    }
                    





                    //Añadimos un botón por si el usuario quiere subir más imágenes
                    echo "<a href='subirImagenes.php?usuario=" . $usuario . "' class='galeria'>Subir más imágenes</a>";
                ?>
            </div>          
        </div>        
    </body>
</html>