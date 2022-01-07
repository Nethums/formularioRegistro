<?php

 /* Ejecutando este fichero crearemos la BD en nuestro servidor de BD.
    Los datos de conexión son los siguientes, comprueba que coinciden con los tuyos, sino no funcionará.
    Los leeremos de config.php
    $db_hostname = "localhost";
    $db_nombre = "evaluable_2021";
    $db_usuario = "root";
    $db_clave = "";
*/ 

 //En config.php tenemos los valores de conexión a la BD
include ('../libs/config.php');

try {
    /*
     * Conectamos
     * No le pasamos nombre de BD porque vamos a crearla
     */
    $pdo = new PDO('mysql:host='.$db_hostname, $db_usuario, $db_clave);
    //UTF8  
    $pdo->exec("set names utf8");
    // Accionamos el uso de excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Leemos el fichero que contiene el sql
    $sqlBD = file_get_contents("evaluable_2021.sql");
    //Ejecutamos la consulta
    if ($pdo->exec($sqlBD)) {
        echo "La BD ha sido creada.<br><br><br>";
        echo "Añadiendo aficiones:<br>";
        echo "--------------------------<br><br>";
    } else {
        echo "No se ha podido crear la base de datos " . $db_nombre . "<br><br>";
    }

    foreach ($aficionesLista as $aficion) {
        try {
            // Preparamos consulta
            $stmt = $pdo->prepare("INSERT INTO aficiones (aficion) values (?)");
            
            // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden
            $stmt->bindParam(1, $aficion);
    
            // Excecute - Ejecutamos la sentencia. Nos de vuelve true o false
            if ($stmt->execute()) {
                echo "- La afición '" . $aficion . "' ha sido añadida.<br>";
            } else {
                echo "- La afición '" . $aficion . "' no ha podido ser añadida.<br>";
            }     
        } catch (PDOException $e) {
            // En este caso guardamos los errores en un archivo de errores log
            error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
            // guardamos en ·errores el error que queremos mostrar a los usuarios
            $errores['datos'] = "Ha habido un error <br>";
        }
    }
} catch (PDOException $e) {
    // En este caso guardamos los errores en un archivo de errores log
    error_log($e->getMessage() . "## Fichero: " . $e->getFile() . "## Línea: " . $e->getLine() . "##Código: " . $e->getCode() . "##Instante: " . microtime() . PHP_EOL, 3, "logBD.txt");
    // guardamos en ·errores el error que queremos mostrar a los usuarios
    $errores['datos'] = "Ha habido un error <br>";
}



?>

