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
        //echo "<input type='checkbox' name='".$name." value=$opcion>$opcion</input>";
        echo "<input type='checkbox' name='" . $name . "[]' value='" . $opcion . "'>". $opcion . ".<br>";
    }
    //echo "<input type='checkbox' name='".$name."[]' value='" . $opciones[$i] . "'>". $opciones[$i] . ".<br>";
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
            $errores[$campo] = "El $campo no es válido.";
         return FALSE;
        }
    } else {
        if ((preg_match("/[A-Za-zÑñ$espacios]{" . $min . "," . $max . "}$/u$case", sinTildes($text)))) {
            return TRUE;
        } else {
            $errores[$campo] = "El $campo no es válido.";
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

/*  */
function cFecha(string $text, string $campo, array &$errores, string $formato = "0") {
    /* Función que valida fechas.
    * Por defecto en formato dd-mm-aaa. Caso 1 mm/dd/aaaa. Caso 2 aaaa/mm/dd
    * Ponemos como caso por defecto el que utilice nuestro formulario
    * Permite separador / o -
    */
       
   $arrayFecha = preg_split("/[\/-]/", $text);
   
   $regex = '/(\d{4})/';    
   if(! preg_match($regex, $arrayFecha[2]) || $arrayFecha[2] < 1950 || $arrayFecha[2] > 2021 ) {
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

?>