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
                } else
                    echo "No se ha insertado ningún registro";
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