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

function sinEspacios1($text) {
    $texto = trim(preg_replace('/ +/', ' ', $text));
    return $texto;
}

function sinEspacios2(string $text, string $espacio = " "){
    $texto = trim(preg_replace('/ +/', $espacio, $text));
    return $texto;
}

function recoge(string $var, bool $espacios = FALSE) {
    $espacios = $espacios ? "" : " ";
    if (isset($_REQUEST[$var]))
        $tmp = strip_tags(sinEspacios2($_REQUEST[$var], $espacios));
        else
            $tmp = "";
            
            return $tmp;
}

function recogeCheck(string $text) {
    if (isset($_REQUEST[$text])){
        return TRUE;
    } else{
         return FALSE;
    }
}

function recogeRadio(string $text) {
    if (isset($_REQUEST[$text]))
        return strip_tags(sinEspacios($_REQUEST[$text]));
        else
            return FALSE;
}

function cGeneral(string $text, string $campo, array $errores, string $regex) {
    /* Función que pásandole la expresión regular nos hace la validación. Sirve para casos de validaciones especiales. */
    if (preg_match($regex, $text))
        return TRUE;
        else {
            $errores[$campo] = "El $campo no es correcto";
            return FALSE;
        }
}

function cTexto(string $text, string $campo, array &$errores, int $max = 30, int $min = 1, string $espacios = " ", string $case = "i") {
    /* No es case sensitive
      Admite letras y números
      Permite un espacio entre palabras
      Cadenas de longitud entre 1 y 30.
    */
    if ((preg_match("/[A-Za-zÑñ0-9$espacios]{" . $min . "," . $max . "}$/u$case", sinTildes($text)))) {
        
        return TRUE;
    }
    $errores[$campo] = "El $campo sólo puede contener letras y números.";
    return FALSE;
}

function cNum(string $num, string $campo, array &$errores, int $max = 10,int $min = 1) {
    /* Cadenas numéricas por defecto entre 1 y 10 dígitos */
    if ((preg_match("/[0-9]{" . $min . "," . $max . "}$/", $num))) {
        
        return TRUE;
    }
    $errores[$campo] = "El $campo solo puede contener números de $min a $max dígitos";
    return FALSE;
}

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

function cTelefono(string $text, string $campo, array &$errores) {
    /* Validación del teléfono permitiendo de manera opcional ponerle el prefijo internacional español. */
    $regex = "/^(\+34|0034){0,1}[1-9][0-9]{8}$/";
    if (preg_match($regex, preg_replace("/[\s-]+/", "", $text)))
        return TRUE;
        else {
            $errores[$campo] = "El $campo no es correcto";
            return FALSE;
        }
}

function cMail(string $text, string $campo, array &$errores) {
    if ((filter_var($text, FILTER_VALIDATE_EMAIL))) {
        
        return TRUE;
    }
    $errores[$campo] = "El $campo no es correcto";
    return FALSE;
}

function cTextarea(string $text, string $campo, array &$errores, int $max = 300, int $min = 1) {
    /* Función que valida el contenido de un textarea. Podemos seleccionar el número máximo de caracteres. */
    if ((mb_strlen($text) >=$min && mb_strlen($text)<=$max)) {
        
        return TRUE;
    }
    $errores[$campo] = "El $campo permite de $min hasta $max caracteres";
    return FALSE;
}

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

function cDni(string $text, string $campo, array &$errores) {
    $text = strtoupper($text);
    $regex = "/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/";
    if (preg_match($regex, $text))
        
        return cLetraDni($text);
        else {
            $errores[$campo] = "El $campo no es correcto";
            return FALSE;
        }
}

function cNie(string $text, string $campo, array &$errores) {
    $text = strtoupper($text);
    $regex = "/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/";
    if (preg_match($regex, $text)) {
        switch ($text[0]) {
            
            case "X":
                $text[0] = 0;
                return cLetraDni($text);
                break;
            case "Y":
                $text[0] = 1;
                return cLetraDni($text);
                break;
            case "Z":
                $text[0] = 2;
                return cLetraDni($text);
                break;
            default:
                $errores[$campo] = "El $campo no es correcto";
                return FALSE;
        }
    } else {
        $errores[$campo] = "El $campo no es correcto";
        return FALSE;
    }
}

function cLetraDni(string $text) {
    $letras = array(
        "T","R","W","A","G","M","Y","F","P","D","X","B","N","J","Z","S","Q","V","H","L","C","K","E"
    );
    $numeros = intval($text);
    $resto = $numeros % 23;
    return $letras[$resto] == $text[8] ? TRUE : FALSE;
}

function cFile(string $campo, string $rutaFinal, array $extensionesValidas, array &$errores, bool $requerido = TRUE) {
    
    /*
     $rutaImagenes="imagenes/";
     $extensionesValidas=["image/jpeg","image/gif"];
     Por defecto será requerido
    */

    if ($requerido) {
        if ($_FILES[$campo]['error'] != 0) {
            $errores[$campo] = "No se ha podido subir el fichero. Inténtalo de nuevo.";
            return FALSE;     
        } else {
            // Guardamos el nombre original del fichero
            $nombreArchivo = $_FILES[$campo]['name'];
            // Guardamos nombre del fichero en el servidor
            $directorioTemp = $_FILES[$campo]['tmp_name'];
            $extension = $_FILES[$campo]['type'];
            
            // Comprobamos la extensión del archivo dentro de la lista que hemos definido al principio
            if (! in_array($extension, $extensionesValidas)) {
                $errores[] = "La extensión del archivo no es válida o no se ha subido ningún archivo";
                return FALSE;
            }
            
            // Almacenamos el archivo en ubicación definitiva        
            if (is_file($rutaFinal . $nombreArchivo)) {
                // Si existe una imagen con el mismo nombre le añadimos al final lo que devuelve time() precedido de un _
                $nombrePartes = explode(".", $nombreArchivo);
                $nombreFinal = $nombrePartes[0] . "_" . time() . "." . $nombrePartes[1];
                $nombreArchivo = $nombreFinal;
            }
            
            // Movemos el fichero a la ubicación definitiva
            if (move_uploaded_file($directorioTemp, $rutaFinal . $nombreArchivo)) {
                // En este caso devolvemos sólo el nombre del fichero sin la ruta
                return $nombreArchivo;
            } else {
                $errores[] = "Error: No se puede mover el fichero a su destino";
                return FALSE;
            }
        }      

    }

    if (!$requerido) {
        if ($_FILES[$campo]['error'] = 4 && $_FILES[$campo]['size'] == 0) {
            return TRUE;
        }

        if ($_FILES[$campo]['error'] = 4 && $_FILES[$campo]['size'] > 0) {
            // Guardamos el nombre original del fichero
            $nombreArchivo = $_FILES[$campo]['name'];
            // Guardamos nombre del fichero en el servidor
            $directorioTemp = $_FILES[$campo]['tmp_name'];
            $extension = $_FILES[$campo]['type'];
            
            // Comprobamos la extensión del archivo dentro de la lista que hemos definido al principio
            if (! in_array($extension, $extensionesValidas)) {
                $errores[] = "La extensión del archivo no es válida o no se ha subido ningún archivo";
                return FALSE;
            }
            
            // Almacenamos el archivo en ubicación definitiva        
            if (is_file($rutaFinal . $nombreArchivo)) {
                // Si existe una imagen con el mismo nombre le añadimos al final lo que devuelve time() precedido de un _
                $nombrePartes = explode(".", $nombreArchivo);
                $nombreFinal = $nombrePartes[0] . "_" . time() . "." . $nombrePartes[1];
                $nombreArchivo = $nombreFinal;
            }
            
            // Movemos el fichero a la ubicación definitiva
            if (move_uploaded_file($directorioTemp, $rutaFinal . $nombreArchivo)) {
                // En este caso devolvemos sólo el nombre del fichero sin la ruta
                return $nombreArchivo;
            } else {
                $errores[] = "Error: No se puede mover el fichero a su destino";
                return FALSE;
            }
        }
    }
}

function generarCheckbox(array &$opciones, string $name) {
    $opcionesCheck = count($opciones);
    for ($i=0; $i < $opcionesCheck; $i++) {
        echo "<input type='checkbox' name='".$name."[]' value='" . $opciones[$i] . "'>". $opciones[$i] . ".<br>";
    }
}

function generarSelect(array &$opciones, string $name) {
    $opcionesSelect = count($opciones);
    echo "<select name=". $name .">";
    for ($i=0; $i < $opcionesSelect; $i++) {
        echo "<option value='" . $opciones[$i] . "'>". $opciones[$i] . "</option><br>";
    }
    echo"</select><br>";
}


?>