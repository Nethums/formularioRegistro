<?php
    require_once("bGeneral.php");
    require_once("config.php");

    $errores = [];
    $usuario = $_GET['usuario'];
    
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

        if (! cSubirImagenesUsuario("fotoUsuario", $extensionesValidas, $errores)) {
            $error = TRUE;
        }

        

        if(!$error) {
            /* Depende de la opción que haya elegido el usuario en Select guardaremos la imagen en una carpeta diferente */
            if ($opcionSelect == "privada") {
                $usuario = $_GET['usuario'];
                $path = $directorioUsuarios . $usuario; //$directorioUsuarios está en libs/config.php

                /* Conectamos con la base de datos e introducimos el nuevo usuario en la base de datos */
                
                try {
                    include ('../libs/bConecta.php');
                    
                    $consulta = "SELECT id_user 
                                 FROM usuarios 
                                 WHERE user=:usuario";
                    $result = $pdo->prepare($consulta);
                    $result->execute(array(":usuario" => $usuario));                    
                    $test = $result -> rowCount();
                    $idUsuario = $result->fetchColumn();  //Sólo nos interesa el valor de la id por eso utilizamos fetchColumn en lugar de fetch() o fetchAll()
                                        
                    // Preparamos consulta
                    $stmt = $pdo->prepare("INSERT INTO imagenes (rutaImagen, idUser, descripcion) values (?, ?, ?)");
                    
                    /* Preparamos el nombre de la foto para quitarle todos los espacios antes de meterla en la base de datos */
                    
                    $nombreFotoSinEspacios = reemplazarEnFiles ("fotoUsuario", "name", " ", "_");
                    /* Aseguramos que no hayan imágenes duplicadas, si la hay le cambiamos el nombre y añadimos time() */
                    
                    $imagenesCarpetaUsuario = scandir("../" . $directorioUsuarios . $usuario . "/");

                    if(in_array($nombreFotoSinEspacios, $imagenesCarpetaUsuario )) {
                        echo "LA FOTO EXISTE <br><br>";
                        $nombreImagenFinal = cambiarNombreFotoSiEstaEnDirectorio ($directorioUsuarios, $usuario, $nombreFotoSinEspacios, "privada");
                        $rutaImagenUsuario = $directorioUsuarios . $usuario . "/" . $nombreImagenFinal;
                    } else {
                        echo "LA FOTO NO EXISTE <br><br>";
                        $rutaImagenUsuario = $directorioUsuarios . $usuario . "/" . $nombreFotoSinEspacios;
                    }

                    $descripcionFoto = $_REQUEST['descripcionFoto'];
                    
                    /* Añadimos la foto a la carpeta del usuario después de analizarlo, porque sino siempre encuentra una foto y cambia el nombre ya que primero la sube y luego la busca */
                    $fotoUsuario = cSubirImagen("fotoUsuario", $usuario, $directorioUsuarios, $extensionesValidas, $errores, "privada");
                    
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

                $usuario = $_GET['usuario'];
                $path = $directorioUsuarios . $usuario; //$directorioUsuarios está en libs/config.php
                //$fotoUsuario = cSubirImagen("fotoUsuario", $usuario, $directorioUsuarios, $extensionesValidas, $errores, "publica");

                /* Conectamos con la base de datos e introducimos el nuevo usuario en la base de datos */
                
                try {
                    include ('../libs/bConecta.php');
                    
                    $consulta = "SELECT id_user 
                                 FROM usuarios 
                                 WHERE user=:usuario";
                    $result = $pdo->prepare($consulta);
                    $result->execute(array(":usuario" => $usuario));                    
                    $test = $result -> rowCount();
                    $idUsuario = $result->fetchColumn();  //Sólo nos interesa el valor de la id por eso utilizamos fetchColumn en lugar de fetch() o fetchAll()
                                        
                    // Preparamos consulta
                    $stmt = $pdo->prepare("INSERT INTO imagenes (rutaImagen, idUser, descripcion) values (?, ?, ?)");
                    
                    /* Preparamos el nombre de la foto para quitarle todos los espacios antes de meterla en la base de datos */
                    
                    $nombreFotoSinEspacios = reemplazarEnFiles ("fotoUsuario", "name", " ", "_");
                    /* Aseguramos que no hayan imágenes duplicadas, si la hay le cambiamos el nombre y añadimos time() */
                    
                    $imagenesCarpetaUsuario = scandir("../" . $directorioUsuarios);
                    
                    if(in_array("$nombreFotoSinEspacios", $imagenesCarpetaUsuario )) {
                        echo "LA FOTO EXISTE <br><br>";
                        $nombreImagenFinal = cambiarNombreFotoSiEstaEnDirectorio ($directorioUsuarios, $usuario, $nombreFotoSinEspacios, "publica");
                        $rutaImagenUsuario = $directorioUsuarios. $nombreImagenFinal;
                    } else {
                        echo "LA FOTO NO EXISTE <br><br>";
                        $rutaImagenUsuario = $directorioUsuarios . $nombreFotoSinEspacios;
                    }

                    $descripcionFoto = $_REQUEST['descripcionFoto'];
                    
                    /* Añadimos la foto a la carpeta del usuario después de analizarlo, porque sino siempre encuentra una foto y cambia el nombre ya que primero la sube y luego la busca */
                    $fotoUsuario = cSubirImagen("fotoUsuario", $usuario, $directorioUsuarios, $extensionesValidas, $errores, "publica");
                    
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
        } else {
            header('Location: ../pages/subirImagenes.php?usuario=' . $usuario .'&foto=error');
        }

    }


?>