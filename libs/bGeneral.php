<?php

function cabecera($titulo = "Ejemplo") {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta http-equiv=”Content-Type” content=”text/html; charset=UTF-8″ />
        <link rel="stylesheet" href="css/estilos.css" type="text/css">
        <title>
        	<?php
                echo $titulo;
            ?>
        </title>         
    </head>
    <body>
    <?php
}

function pie() {
    echo "</body>
	</html>";
}

function generarCheckbox(array &$opciones, string $name) {
    foreach($opciones as $opcion){
        echo "<input type='checkbox' name='" . $name . "[]' value='" . $opcion . "'>". $opcion . ".<br>";
    }
}

function sinTildes($text) {
    $no_permitidas = array(
        "á", "é", "í", "ó", "ú",
        "Á", "É", "Í", "Ó", "Ú",
        "à", "è", "ì", "ò", "ù",
        "À", "È", "Ì", "Ò", "Ù"
    );
    
    $permitidas = array(
        "a", "e", "i", "o", "u",
        "A", "E", "I", "O", "U",
        "a", "e", "i", "o", "u",
        "A", "E", "I", "O", "U"
    );
    
    $texto = str_replace($no_permitidas, $permitidas, $text);
    return $texto;
}

/* Función para quitar espacios */
function sinEspacios(string $text, string $espacio = " "){
    $texto = trim(preg_replace('/ +/', $espacio, $text));
    return $texto;
}

/* Función que recoge el valor. Se puede elegir si se quiere quitar los espacios (TRUE) o dejarlos (FALSE) */
function recoge(string $var, bool $espacios = FALSE) {
    $espacios = $espacios ? "" : " ";
    if (isset($_REQUEST[$var]))
        $tmp = strip_tags(sinEspacios($_REQUEST[$var], $espacios));
        else
            $tmp = "";
            
    return $tmp;
}

/* Función que comprueba que hay al menos 1 opción elegida */
function recogeCheck(string $text) {
    if (isset($_REQUEST[$text])){
        return TRUE;
    } else{
         return FALSE;
    }
}

/* Función que comprueba una cadena de texto con los parámetros introducidos en la función */
function cTexto(string $text, string $campo, array &$errores, int $max = 30, int $min = 1, string $espacios = " ", string $case = "i", bool $numeros = FALSE) {
    /* No es case sensitive
       Admite letras y números
       Permite un espacio entre palabras
       Cadenas de longitud entre 1 y 30
    */
    /* Si permitimos números entrará en el IF y sino permiten números iremos al ELSE */
    if ($numeros) {
        if ((preg_match("/[A-Za-zÑñ0-9$espacios]{" . $min . "," . $max . "}$/u$case", sinTildes($text)))) {
            return TRUE;
        } else {
            $errores[$campo] = "El campo $campo no es válido.";
         return FALSE;
        }
    } else {
        if ((preg_match("/[A-Za-zÑñ$espacios]{" . $min . "," . $max . "}$/u$case", sinTildes($text)))) {
            return TRUE;
        } else {
            $errores[$campo] = "El campo $campo no es válido.";
            return FALSE;
        }
    }    
}

/* Función que comprueba si el nombre del usuario es válido */
function cUsuario(string $text, string $campo, array &$errores) {
    /* No es case sensitive
       Debe empezar por una letra o el carácter _
       Admite letras, números y los caracteres &$_
       Cadenas de longitud entre 1 y 12
    */
    if ((preg_match("/^[A-Za-zÑñ_]{1}[A-Za-zÑñ0-9\$\\_&]{1,11}$/ui", sinTildes($text)))) {        
        return TRUE;
    } else {
        $errores[$campo] = "El $campo no es válido.";
        return FALSE;
    }    
}

/* Función que comprueba si la contraseña es válida */
function cContrasena(string $text, string $campo, array &$errores) {
    /* No es case sensitive
       Admite letras, números y los caracteres &$_\/-+
       Cadenas de longitud entre 1 y 15
    */
    if ((preg_match("/^[A-Za-zÑñ0-9\/\\\+\$\-\*_]{1,15}$/ui", sinTildes($text)))) {        
        return TRUE;
    } else {
        $errores[$campo] = "La $campo no es válida.";
        return FALSE;
    }    
}

/* Función que recoge las opciones elegidas por el usuario y comprueba que están dentro de las opciones posibles dentro del array en libs/config.php */
function cCheckbox(string $campo, array &$listaPosibles, array &$errores) {     

    $valido = FALSE;

    if(empty($_REQUEST[$campo])) {
        $errores[$campo] = "Debe elegir al menos un valor para el ". $campo;
        return FALSE;
    } else {
        $numeroCheckboxMarcados = count($_REQUEST[$campo]);
        foreach (($_REQUEST[$campo]) as $valorGenero) {
            if (in_array($valorGenero, $listaPosibles)) {
                $valido = TRUE;
                break;
            } 
        }
        if($valido) {
            return TRUE;
        } else {
            $errores[$campo] = "El valor del ". $campo . " no es válido.";
            return FALSE;
        }
    }
}

/* Función que recoge la opción del Select seleccionada por el usuario y comprueba si está en la lista de las posibles opciones. Este array se pasa como parámetro */
function cSelect(string $campo, array &$listaPosibles, array &$errores) {     

    $valido = FALSE;

    if (in_array($_REQUEST[$campo], $listaPosibles)) {
        $valido = TRUE;
    } 

    if($valido) {
        return TRUE;
    } else {
        $errores[$campo] = "El valor del ". $campo . " no es válido.";
        return FALSE;
    }    
}


/* Función para comprobar que la fecha introducida por el usuario es válida y al mismo tiempo devuelve la fecha ordenada para la base de datos aaaa/mm/dd */
function cFecha(string $text, string $campo, array &$errores, string $formato = "0") {
    /* Función que valida fechas.
    * Por defecto en formato dd-mm-aaa. Caso 1 mm/dd/aaaa. Caso 2 aaaa/mm/dd
    * Ponemos como caso por defecto el que utilice nuestro formulario
    * Permite separador / o -
    */
       
   $arrayFecha = preg_split("/[\/-]/", $text);
   
   $regex = '/(\d{4})/';    
   if(! preg_match($regex, $arrayFecha[2]) || $arrayFecha[2] < 1700 || $arrayFecha[2] > 2021 ) {
       $errores[$campo] = "El año es incorrecto";
       return FALSE;
   }
   
   if (count($arrayFecha) == 3) {
       switch ($formato) {
           case ("0"):
               return checkdate($arrayFecha[1], $arrayFecha[0], $arrayFecha[2]);
               break;
               
           case ("1"):
               return checkdate($arrayFecha[0], $arrayFecha[1], $arrayFecha[2]);
               break;
               
           case ("2"):
               return checkdate($arrayFecha[1], $arrayFecha[2], $arrayFecha[0]);
               break;
           default:
               $errores[$campo] = "El $campo tiene errores";
               return FALSE;
       }
   } else {
       $errores[$campo] = "El $campo tiene errores";
       return FALSE;
   }
}

/* Función para comprobar el Textarea del formulario */
function cTextarea(string $text, string $campo, array &$errores, int $max = 300, int $min = 1) {
    /* Función que valida el contenido de un textarea. Podemos seleccionar el número máximo de caracteres. */
    if ((mb_strlen($text) >=$min && mb_strlen($text)<=$max)) {
        
        return TRUE;
    }
    $errores[$campo] = "El $campo permite de $min hasta $max caracteres";
    return FALSE;
}

/*  */
function cSubirImagenesUsuario(string $campo, array $extensionesValidas, array &$errores) {
    
    if ($_FILES[$campo]['error'] != 0) {
        $errores[$campo] = "No se ha podido subir el fichero. Inténtalo de nuevo.";
        return FALSE;     
    } else {
        return TRUE;
    }
    // Guardamos el nombre original del fichero
    $nombreArchivo = $_FILES[$campo]['name'];
    // Guardamos nombre del fichero en el servidor
    $directorioTemp = $_FILES[$campo]['tmp_name'];
    $extension = $_FILES[$campo]['type'];
    
    // Comprobamos la extensión del archivo dentro de la lista que hemos definido al principio
    if (! in_array($extension, $extensionesValidas)) {
        $errores[] = "La extensión del archivo no es válida o no se ha subido ningún archivo";
        return FALSE;
    }  else {
        return TRUE;
    }
}


/* Función que comprueba la foto enviada por el usuario. Comprueba que tiene una extensión válida dentro de las posibles (especificadas en libs/config.php). Si supera la comprobación sube la foto a la carpeta fotosPerfil */
function cFotoPerfil(string $campo, string $usuario, string $path, string $directorioFotosPerfil, array $extensionesValidas, array &$errores) {
    
    $directorioTemp = $_FILES[$campo]['tmp_name'];
    $extension = $_FILES[$campo]['type'];
    $nombreArchivo = $usuario . ".jpg";    

    /* Si hay un error diferente a 0 y 4 es porque no se ha podido subir la imagen */
    if ($_FILES[$campo]['error'] != 0 && $_FILES[$campo]['error'] != 4) {
        $errores[$campo] = "No se ha podido subir el fichero. Inténtalo de nuevo.";
        return FALSE;     
    } elseif ($_FILES[$campo]['error'] = 4 && $_FILES[$campo]['size'] == 0) {
        /* Si el usuario no ha subido ninguna foto, le asignamos una foto por defecto */
        $imagePath = "../img/usuario_sin_foto.jpg";
        $newPath = $path . "/" . $nombreArchivo;

        if ( ! is_file($newPath)) {
            if (! copy($imagePath , $newPath)) {
                return FALSE;
             }
             else { 
                 return TRUE;
             }
        }
    } else {
        /* El usuario ha subido una foto y hay que analizarla */
        $directorioTemp = $_FILES[$campo]['tmp_name'];    
        $nombreArchivo = $_FILES[$campo]['name'];
        $nombrePartes = explode(".", $nombreArchivo); 
        //Necesitamos la extensión de la foto que ha subido el usuario, por eso nos quedamos con el segundo item del array que es la extensión
        $nombreFinal = $usuario . "."  . $nombrePartes[1];
        
        // Comprobamos la extensión del archivo dentro de la lista que hemos definido al principio
        if (! in_array($extension, $extensionesValidas)) {
            $errores[] = "La extensión del archivo no es válida o no se ha subido ningún archivo";
            return FALSE;
        }

        $rutaUsuario = "../" . $directorioFotosPerfil . $nombreFinal;
        if (is_file($rutaUsuario)) {
            echo "<br>Ya existe una foto de perfil para el usuario.";
        } else {
            if (move_uploaded_file($directorioTemp, $rutaUsuario)) {
                // En este caso devolvemos sólo el nombre del fichero sin la ruta
                echo "<br>Se ha subido la foto de perfil del usuario";
                return TRUE;
            } else {
                $errores[] = "Error: No se puede mover el fichero a su destino";
                return FALSE;
            }
        }
    }    
}

/* Función para el formularioSubirImagenes. Dependiendo de la opción que haya elegido, la imagen se guardará en una carpeta diferente */
function cSubirImagen(string $campo, string $usuario, string $directorioUsuarios, array $extensionesValidas, array &$errores, string $publicaPrivada) {

    $directorioTemp = $_FILES[$campo]['tmp_name'];
    $extension = $_FILES[$campo]['type'];
    //$nombreArchivo = $_FILES[$campo]['name'];
    //No se pueden guardar nombres de fotos con espacios, por tanto cambiamos los espacios por _  
    $nombreFotoSinEspacios = reemplazarEnFiles ("fotoUsuario", "name", " ", "_");

    if($_FILES[$campo]['error'] == 0 && $_FILES[$campo]['size'] > 0) {
        /* El usuario ha subido una foto y hay que analizarla */        
        $nombrePartes = explode(".", $nombreFotoSinEspacios); 
        //Necesitamos la extensión de la foto que ha subido el usuario, por eso nos quedamos con el segundo item del array que es la extensión
        $extensionImagen = $nombrePartes[1];

        // Falta analizar qué pasa si la foto ya está subida, habría que añadirle time()
        
        // Comprobamos la extensión del archivo dentro de la lista que hemos definido al principio
        if (! in_array($extension, $extensionesValidas)) {
            $errores[] = "La extensión del archivo no es válida o no se ha subido ningún archivo";
            return FALSE;
        }

        if ($publicaPrivada == "privada") {
            if (is_file("../" . $directorioUsuarios . $usuario .'/' . $nombreFotoSinEspacios)) {
                // Si existe una imagen con el mismo nombre le añadimos al final lo que devuelve time() precedido de un _
                $nombrePartes = explode(".", $nombreFotoSinEspacios);
                $nombreFinal = $nombrePartes[0] . "_" . time() . "." . $nombrePartes[1];
                $nombreFotoSinEspacios = $nombreFinal;
            }
             
            $rutaUsuario = "../" .$directorioUsuarios . $usuario . '/' . $nombreFotoSinEspacios;
            if (move_uploaded_file($directorioTemp, $rutaUsuario)) {
                // En este caso devolvemos sólo el nombre del fichero sin la ruta
                return TRUE;
            } else {
                $errores[] = "Error: No se puede mover el fichero a su destino";
                return FALSE;
            }
        }

        if ($publicaPrivada == "publica") {
            if (is_file("../" . $directorioUsuarios . $nombreFotoSinEspacios)) {
                // Si existe una imagen con el mismo nombre le añadimos al final lo que devuelve time() precedido de un _
                $nombrePartes = explode(".", $nombreFotoSinEspacios);
                $nombreFinal = $nombrePartes[0] . "_" . time() . "." . $nombrePartes[1];
                $nombreFotoSinEspacios = $nombreFinal;
            }
             
            $rutaUsuario = "../" .$directorioUsuarios . $nombreFotoSinEspacios;
            if (move_uploaded_file($directorioTemp, $rutaUsuario)) {
                // En este caso devolvemos sólo el nombre del fichero sin la ruta
                return TRUE;
            } else {
                $errores[] = "Error: No se puede mover el fichero a su destino";
                return FALSE;
            }
        }
        
    }
}

/* Función donde se le pasa un campo y dos valores: lo que queremos sustituir y por lo que se sustituye. Devuelve la cadena con el reemplazo */
function reemplazarEnFiles (string $campo, string $atributo, string $aparicionEnString, string $reemplazo) {
    $cadenaOriginal = $_FILES[$campo][$atributo];  
    //Cambiar $aparicionEnString por $reemplazo
    $cadenaFinal = str_replace($aparicionEnString, $reemplazo, $cadenaOriginal);
    return $cadenaFinal;
}

/*  */
function cambiarNombreFotoSiEstaEnDirectorio (string $directorio, string $usuario, string $nombreArchivo, string $publicaPrivada) {

    if ($publicaPrivada == "privada") {
        $rutaFoto = "../" . $directorio . $usuario . "/" . $nombreArchivo;
        if (is_file($rutaFoto)) {
            // Si existe una imagen con el mismo nombre le añadimos al final lo que devuelve time() precedido de un _
            $nombrePartes = explode(".", $nombreArchivo);
            $nombreFinal = $nombrePartes[0] . "_" . time() . "." . $nombrePartes[1];
            return $nombreFinal;
        }         
    }
    
    if ($publicaPrivada == "publica") {
        if (is_file("../" . $directorio . $nombreArchivo)) {
            // Si existe una imagen con el mismo nombre le añadimos al final lo que devuelve time() precedido de un _
            $nombrePartes = explode(".", $nombreArchivo);
            $nombreFinal = $nombrePartes[0] . "_" . time() . "." . $nombrePartes[1];
            $nombreArchivo = $nombreFinal;
            return $nombreArchivo;
        }
    }
}





?>