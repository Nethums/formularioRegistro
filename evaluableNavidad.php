<?php
    require("libs/bGeneral.php");
    require("libs/config.php");
    
    
    cabecera("Evaluable Navidad.");
    $errores = [];
    print_r($_REQUEST);
    
    
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

        if (! cFecha($fechaNacimiento, "fechaNacimiento", $errores, "0")) {
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
            echo "<pre>";
            print_r($_REQUEST);
            echo "</pre>";
            echo "<br><br><br><b>FORMULARIO PROCESADO CORRECTAMENTE</b>";

            /* Comprobamos si existía un directorio con el nombre del usuario y se crea si no existe */
            $path = "directorioUsuarios/$usuario";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                echo "<br>El directorio del usuario " . $usuario . " ha sido creado correctamente.";
            } else {
                echo "<br>El directorio del usuario " . $usuario . " ya existía.";
            }    
            
            /* Prueba de que el usuario no sube una foto y copiamos la imagen por defecto y cambiamos el nombre de la foto por la del usuario */
            if(cFotoPerfil2("fotoPerfil", $usuario, $path, $rutaFinal, $extensionesValidas, $errores)) {
                echo "<br>Foto copiada con éxito.";
            } else {
                echo "<br>No se ha copiado nada.";
            }

            echo "<pre>";
            print_r($_FILES);
            echo "</pre>";

        } else {
            //Sacamos por pantalla los errores ocurridos y el formulario para que lo reenvie
            echo "<pre>";
            print_r($_REQUEST);
            echo "</pre>";     
            
            echo "<pre>";
            print_r($_FILES);
            echo "</pre>";
            
            print_r($errores);   
            echo "<br><br><br>";
            require ("forms/form.php"); 
        }
    }

    pie();    
?>