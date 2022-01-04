<?php
    require_once("bGeneral.php");
    require_once("config.php");

    $errores = [];

    if (isset($_REQUEST["subirImagen"])) {
        $error = FALSE; 

        /* Recogemos los datos del formularioSubirImagenes */
        $opcionSelect = recoge("select");
        $descripcionFotoTextArea = recoge("descripcionFoto");

        /* Comprobamos que los datos sean válidos */
        $opcionesValidas = ["publica", "privada"];
        if (! cSelect("select", $opcionesValidas, $errores)) {
            $error = TRUE;
        }

        if (! cTextarea($descripcionFotoTextArea, "descripcionFoto", $errores, 80, 0)) {
            $error = TRUE;
        }

        if(!$error) {
            /* Depende de la opción que haya elegido el usuario en Select guardaremos la imagen en una carpeta diferente */
            if ($opcionSelect == "privada") {
                $usuario = $_GET['usuario'];
                $path = $directorioUsuarios . $usuario; //$directorioUsuarios está en libs/config.php
                $fotoUsuario = cSubirImagen("fotoUsuario", $usuario, $directorioUsuarios, $extensionesValidas, $errores, "privada");

                /* Conectamos con la base de datos e introducimos el nuevo usuario en la base de datos */
                
                try {
                    include ('../libs/bConecta.php');
                    echo $usuario ."<br>";
                    
                    $consulta = "SELECT id_user 
                                 FROM usuarios 
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

                if ($fotoUsuario) {
                    //Devolvemos por $GET el nombre del usuario y el valor OK para recogerlo en pages/subirImagenes.php
                    header('Location: ../pages/subirImagenes.php?usuario=' . $usuario .'&foto=ok');
                } else {
                    //Devolvemos por $GET el nombre del usuario y el valor ERROR para recogerlo en pages/subirImagenes.php
                    header('Location: ../pages/subirImagenes.php?usuario=' . $usuario .'&foto=error');
                }
            }       

            if ($opcionSelect == "publica") {
                echo "<pre>";
                print_r($_REQUEST);
                echo "</pre>";
                echo "<pre>";
                print_r($_FILES);
                echo "</pre>";

                $usuario = $_GET['usuario'];
                $path = $directorioUsuarios . $usuario; //$directorioUsuarios está en libs/config.php
                $fotoUsuario = cSubirImagen("fotoUsuario", $usuario, $directorioUsuarios, $extensionesValidas, $errores, "publica");

                /* Conectamos con la base de datos e introducimos el nuevo usuario en la base de datos */
                
                try {
                    include ('../libs/bConecta.php');
                    echo $usuario ."<br>";
                    
                    $consulta = "SELECT id_user 
                                 FROM usuarios 
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
                    $rutaImagenUsuario = $directorioUsuarios . $nombreImagenFinal;
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

/*
                if ($fotoUsuario) {
                    //Devolvemos por $GET el nombre del usuario y el valor OK para recogerlo en pages/subirImagenes.php
                    header('Location: ../pages/subirImagenes.php?usuario=' . $usuario .'&foto=ok');
                } else {
                    //Devolvemos por $GET el nombre del usuario y el valor ERROR para recogerlo en pages/subirImagenes.php
                    header('Location: ../pages/subirImagenes.php?usuario=' . $usuario .'&foto=error');
                }
*/


            }

        } else {
            echo "ERROR";
        }

    }


?>