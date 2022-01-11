<?php

$usuario = "Nethuns";

try {
    include ('bConecta.php');  //CAMBIAR

    /* Necesitamos saber la Id del usuario. Para ello hacemos la siguiente consulta */
    $nickUsuario = "SELECT user  
                   FROM usuarios 
                   WHERE user=:usuario";
    $result = $pdo->prepare($nickUsuario);
    $result->execute(array(":usuario" => $usuario));   
    $test = $result -> rowCount();         
    echo $test;

    if ($test) {
        echo "<br><br>EXISTE EL USUARIO";
    } else {
        echo "<br><br>NO EXISTE EL USUARIO";
    }
}   catch (PDOException $e) {
    // En este caso guardamos los errores en un archivo de errores log
    error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
} 



?>