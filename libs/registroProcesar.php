<?php
    require_once("bGeneral.php");
    require_once("config.php");
    
    
    cabecera("Evaluable Navidad");
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


/*   DESCOMENTAR PARA DIRECTORIO
            $path = "directorioUsuarios/$usuario";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                echo "<br>El directorio del usuario " . $usuario . " ha sido creado correctamente.";
            } else {
                echo "<br>El directorio del usuario " . $usuario . " ya existía.";
            }    
 */


            
            /* Prueba de que el usuario no sube una foto y copiamos la imagen por defecto y cambiamos el nombre de la foto por la del usuario */
/*

            if(cFotoPerfil2("fotoPerfil", $usuario, $path, $rutaFinal, $extensionesValidas, $errores)) {
                echo "<br>Foto copiada con éxito.";
            } else {
                echo "<br>No se ha copiado nada.";
            }

            echo "<pre>";
            print_r($_FILES);
            echo "</pre>";
*/
        } else {
            //Sacamos por pantalla los errores ocurridos y el formulario para que lo reenvie
            /*
            echo "<pre>";
            print_r($_REQUEST);
            echo "</pre>";     
            
            echo "<pre>";
            print_r($_FILES);
            echo "</pre>";
            
            echo "<pre>";
            print_r($errores);
            echo "</pre>";  
            echo "<br><br><br>";
            */

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

    pie();    
?>