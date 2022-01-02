<?php
    require_once("bGeneral.php");
    require_once("config.php");
    
    $errores = [];    
    
    if (! isset($_REQUEST['bAceptar'])) {
        // Si no se ha pulsado botón Aceptar se escribe el formulario vacío
        require ("forms/form.php");
    } else {
        // Inicializamos el error a FALSE y si a la hora de validar los datos hay error, salimos del bucle
        $error = FALSE;                
        // Recogemos la información que ha pasado el usuario
        $nombre = recoge("nombre", FALSE);
        $apellidos = recoge("apellidos", FALSE);
        $usuario = recoge("usuario", FALSE);
        $contrasena = recoge("contrasena", FALSE);
        $localidad = recoge("localidad", FALSE);
        $provincia = recoge("provincia", FALSE);
        $fechaNacimiento = recoge("fechaNacimiento", FALSE);
        $aficiones = recogeCheck("aficiones");
        $biografia = recoge("biografia");


        //Validación de los parámetros del usuario
        if (! cTexto($nombre, "nombre", $errores, 30, 1, " ", )) {
            $error = TRUE;
        }

        if (! cTexto($apellidos, "apellidos", $errores, 50, 1, " ", )) {
            $error = TRUE;
        }

        if (! cUsuario($usuario, "usuario", $errores)) {
            $error = TRUE;
        }

        if (! cContrasena($contrasena, "contrasena", $errores)) {
            $error = TRUE;
        }

        if (! cTexto($localidad, "localidad", $errores, 50, 0, " ", )) {
            $error = TRUE;
        }

        if (! cTexto($provincia, "provincia", $errores, 50, 0, " ", )) {
            $error = TRUE;
        }

        if (! cFecha($fechaNacimiento, "fechaNacimiento", $errores)) {
            $error = TRUE;
        }

        if (! cCheckbox("aficiones", $aficionesLista, $errores)) {
            $error = TRUE;
        }

        if (! cTextarea($biografia, "biografia", $errores, 300, 0)) {
            $error = TRUE;
        }

        // Si no hay ningún error pasamos a procesar la imagen del usuario. Si hay algún error, volvemos a pedir el formulario        
        if (! $error) {
            //Necesitamos la extensión de la foto que ha subido el usuario
            if ($nombreArchivo1 = $_FILES['fotoPerfil']['size'] > 0 ) {
                $nombreArchivo1 = $_FILES['fotoPerfil']['name'];
                $nombrePartes = explode(".", $nombreArchivo1);
                $extensionImagen = $nombrePartes[1];
                $fPerfil = $directorioFotosPerfil . $usuario . "." . $nombrePartes[1]; 
            }

            // Si el usuario no ha subido ninguna foto tomamos la extensión .jpg porque es la extensión de la foto por defecto en img/
            if ($nombreArchivo1 = $_FILES['fotoPerfil']['size'] == 0 ) {
                $fPerfil = $directorioFotosPerfil . $usuario . ".jpg";
            }            

            /* Conectamos con la base de datos e introducimos el nuevo usuario en la base de datos */
            try {
                include ('../libs/bConecta.php');
                // Preparamos consulta
                $stmt = $pdo->prepare("INSERT INTO usuarios (user, pass, nombre, apellidos, localidad, provincia, fNacimiento, bio, fPerfil) values (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden
                $stmt->bindParam(1, $usuario);
                $stmt->bindParam(2, $contrasena);
                $stmt->bindParam(3, $nombre);
                $stmt->bindParam(4, $apellidos);
                $stmt->bindParam(5, $localidad);
                $stmt->bindParam(6, $provincia);
                $stmt->bindParam(7, $fecha);
                $stmt->bindParam(8, $biografia);
                $stmt->bindParam(9, $fPerfil);

                // Excecute - Ejecutamos la sentencia. Nos de vuelve true o false
                if ($stmt->execute()) {                    
                    echo "El id del último usuario dado de alta es: " . $pdo->lastInsertId();     
                    $userIdGuardado = $pdo->lastInsertId(); //Guardamos el id del usuario    
                } else {
                    echo "No se ha insertado ningún registro";
                }                  
            } catch (PDOException $e) {
                // En este caso guardamos los errores en un archivo de errores log
                error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                // guardamos en ·errores el error que queremos mostrar a los usuarios
                $errores['datos'] = "Ha habido un error <br>";
            }

            try {
                include ('../libs/bConecta.php');
                // Preparamos consulta        
                $aficionesUSuario = $_REQUEST["aficiones"];
                if (in_array("Videojuegos", $aficionesUSuario)) {
                    $stmt1 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "1"; //Videojuegos es la afición 1

                    $stmt1->bindParam(1, $idUser);
                    $stmt1->bindParam(2, $idAficion);

                    if ($stmt1->execute()) {
                        echo "Se ha insertado la afición Videojuegos.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }   
                
                if (in_array("Series", $aficionesUSuario)) {
                    $stmt2 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "2"; //Series es la afición 2

                    $stmt2->bindParam(1, $idUser);
                    $stmt2->bindParam(2, $idAficion);

                    if ($stmt2->execute()) {
                        echo "Se ha insertado la afición Series.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }   

                if (in_array("Deportes", $aficionesUSuario)) {
                    $stmt3 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "3"; //Deportes es la afición 3

                    $stmt3->bindParam(1, $idUser);
                    $stmt3->bindParam(2, $idAficion);

                    if ($stmt3->execute()) {
                        echo "Se ha insertado la afición Deportes.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }   

                if (in_array("Leer", $aficionesUSuario)) {
                    $stmt4 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "4"; //Leer es la afición 4

                    $stmt4->bindParam(1, $idUser);
                    $stmt4->bindParam(2, $idAficion);

                    if ($stmt4->execute()) {
                        echo "Se ha insertado la afición Leer.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }                
                
                if (in_array("Bailar", $aficionesUSuario)) {
                    $stmt5 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "5"; //Bailar es la afición 2

                    $stmt5->bindParam(1, $idUser);
                    $stmt5->bindParam(2, $idAficion);

                    if ($stmt5->execute()) {
                        echo "Se ha insertado la afición Bailar.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }                   
                
                if (in_array("Senderismo", $aficionesUSuario)) {
                    $stmt6 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "6"; //Senderismo es la afición 6

                    $stmt6->bindParam(1, $idUser);
                    $stmt6->bindParam(2, $idAficion);

                    if ($stmt6->execute()) {
                        echo "Se ha insertado la afición Senderismo.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }                  
                

                if (in_array("Viajar", $aficionesUSuario)) {
                    $stmt7 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "7"; //Viajar es la afición 7

                    $stmt7->bindParam(1, $idUser);
                    $stmt7->bindParam(2, $idAficion);

                    if ($stmt7->execute()) {
                        echo "Se ha insertado la afición Viajar.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }   
                
                if (in_array("Cocinar", $aficionesUSuario)) {
                    $stmt8 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "8"; //Cine es la afición 8

                    $stmt8->bindParam(1, $idUser);
                    $stmt8->bindParam(2, $idAficion);

                    if ($stmt8->execute()) {
                        echo "Se ha insertado la afición Cine.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }                  
                
                if (in_array("Cine", $aficionesUSuario)) {
                    $stmt9 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "9"; //Cine es la afición 9

                    $stmt9->bindParam(1, $idUser);
                    $stmt9->bindParam(2, $idAficion);

                    if ($stmt9->execute()) {
                        echo "Se ha insertado la afición Cocinar.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }   
                
                if (in_array("Música", $aficionesUSuario)) {
                    $stmt10 = $pdo->prepare("INSERT INTO aficionesuser (idUser, idAficion) values (?, ?)");

                    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden

                    $idUser = $userIdGuardado;
                    $idAficion = "10"; //Música es la afición 10

                    $stmt10->bindParam(1, $idUser);
                    $stmt10->bindParam(2, $idAficion);

                    if ($stmt10->execute()) {
                        echo "Se ha insertado la afición Música.<br>";
                    } else {
                        echo "No se han podido insertar las aficiones.<br>";
                    } 
                }   
                
                
            } catch (PDOException $e) {

                // En este caso guardamos los errores en un archivo de errores log
                error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                // guardamos en ·errores el error que queremos mostrar a los usuarios
                $errores['datos'] = "Ha habido un error <br>";
            }
            
            /* Comprobamos si existía un directorio con el nombre del usuario y se crea si no existe */

            $path = "../" . $directorioUsuarios . $usuario;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                echo "<br>El directorio del usuario " . $usuario . " ha sido creado correctamente.";
            } else {
                echo "<br>El directorio del usuario " . $usuario . " ya existía.";
            }   
            
            /* Prueba de que el usuario no sube una foto y copiamos la imagen por defecto y cambiamos el nombre de la foto por la del usuario */
            $fotoPerfilUsuario = "../" . $directorioFotosPerfil;
            if(cFotoPerfil("fotoPerfil", $usuario, $fotoPerfilUsuario, $directorioFotosPerfil, $extensionesValidas, $errores)) {
                echo "<br>Foto copiada con éxito.";
            } else {
                echo "<br>No se ha copiado nada.";
            }
            header('Location: ../index.php?registro=ok');
        } else {
            /* Recogemos los mensajes del array $errores y los guardamos en variables para enviarlos por el método get. En la página errorRegistro.php recogeremos los valores del array $errores enviado por $_GET  */
            $errorNombre = $errores["nombre"];
            $errorApellidos = $errores["apellidos"];
            $errorUsuario = $errores["usuario"];
            $errorContrasena = $errores["contrasena"];
            $errorFechaNacimiento = $errores["fechaNacimiento"];
            $errorAficiones = $errores["aficiones"];
            
            header('Location: ../pages/errorRegistro.php?errores[]='.$errorNombre.'&errores[]='.$errorApellidos.'&errores[]='.$errorUsuario.'&errores[]='.$errorContrasena.'&errores[]='.$errorFechaNacimiento.'&errores[]='.$errorAficiones);
        }
    }  
?>