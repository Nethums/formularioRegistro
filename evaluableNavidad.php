<?php
    require("libs/bGeneral.php");
    require("libs/config.php");
    
    
    cabecera("Evaluable Navidad.");
    $errores = [];
    //print_r($_REQUEST);
    
    
    if (! isset($_REQUEST['bAceptar'])) {
        // Si no se ha pulsado botón Aceptar se escribe el formulario vacío
        require ("forms/form.php");
    } else {
        // Inicializamos el error a FALSE y si a la hora de validar los datos hay error, salimos del bucle
        $error = FALSE;
                
        // Recogemos la información que ha pasado el usuario
        $nombre = recoge("nombre", FALSE);
        $apellidos = recoge("apellidos", FALSE);
        //NombreUsuario
        //Contraseña
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

        //FALTA NombreUsuario
        //FALTA Contraseña

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
        } else {
            //Sacamos por pantalla los errores ocurridos y el formulario para que lo reenvie
            echo "<pre>";
            print_r($_REQUEST);
            echo "</pre>";
            print_r($errores);   
            echo "<br><br><br>";
            require ("forms/form.php"); 
        }
    }

    pie();    
?>