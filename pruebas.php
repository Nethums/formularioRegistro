<?php

$idUsuario = 100;
try {
    include ('libs/bConecta.php'); //IMPORTANTE CAMBIAR ../
    $consulta = "SELECT rutaImagen  
                FROM imagenes 
                WHERE idUser=:usuario";
    $result = $pdo->prepare($consulta);
    $result->execute(array(":usuario" => $idUsuario));                    
    $imagenesPublicasUsuario = $result->fetchAll();  //Sólo nos interesa el valor de la id por eso utilizamos fetchColumn en lugar de fetch() o fetchAll()
    echo "<pre>";
    print_r($imagenesPublicasUsuario);
    echo "</pre>";
    echo "<br>";
}   catch (PDOException $e) {
    // En este caso guardamos los errores en un archivo de errores log
    error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
    // guardamos en ·errores el error que queremos mostrar a los usuarios
    $errores['datos'] = "Ha habido un error <br>";
}   

echo "----------------------------------<br><br>";

$usuario = "Nethuns";

foreach ($imagenesPublicasUsuario as $arrayImagenes) {
    foreach ($arrayImagenes as $rutaImagen => $ruta) {
        if ($rutaImagen === array_key_first($arrayImagenes) && !preg_match("/\b$usuario\b/i", $ruta)) {
            echo $ruta ."<br>";
            //echo "<img src='$ruta'></img><br>";
        }
        
    }
}

   
echo "----------------------------------<br><br>";

$usuario = "Nethuns";

$consultaId = "SELECT id_user  
            FROM usuarios 
            WHERE user=:usuario";
$result = $pdo->prepare($consultaId);
$result->execute(array(":usuario" => $usuario));                    
$idUsuario = $result->fetchColumn();  
echo $idUsuario;

?>