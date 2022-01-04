<?php


try {
    include ('libs/bConecta.php'); //IMPORTANTE CAMBIAR ../
    echo $usuario ."<br>";
    
    $consulta = "SELECT id_user 
                 FROM usuarios 
                 WHERE user=:usuario";
    $result = $pdo->prepare($consulta);
    $result->execute(array(":usuario" => $usuario));                    
    $test = $result -> rowCount();
    $idUsuario = $result->fetchColumn();  //Sólo nos interesa el valor de la id por eso utilizamos fetchColumn en lugar de fetch() o fetchAll()
    print_r($idUsuario);
    echo "<br>";
    
    // Preparamos consulta
    $stmt = $pdo->prepare("INSERT INTO imagenes (rutaImagen, idUser, descripcion) values (?, ?, ?)");
    /* Preparamos el nombre de la foto para quitarle todos los espacios antes de meterla en la base de datos */
    $nombreFotoSinEspacios = reemplazarEnFiles ("fotoUsuario", "name", " ", "_");
    /* Aseguramos que no hayan imágenes duplicadas, si la hay le cambiamos el nombre y añadimos time() */
    $nombreImagenFinal = cambiarNombreFotoSiEstaEnDirectorio ($directorioUsuarios, $usuario, $nombreFotoSinEspacios);
    $rutaImagenUsuario = $directorioUsuarios . $usuario . "/" . $nombreImagenFinal;
    $descripcionFoto = $_REQUEST['descripcionFoto'];
    
    // Bind - Vinculamos cada variable a un parámetro de la sentencia $stmt por orden
    $stmt->bindParam(1, $rutaImagenUsuario);
    $stmt->bindParam(2, $idUsuario);
    $stmt->bindParam(3, $descripcionFoto);

    // Excecute - Ejecutamos la sentencia. Nos de vuelve true o false
    if ($stmt->execute()) {                    
        echo "Se ha subido la foto correctamente";     
        $userIdGuardado = $pdo->lastInsertId(); //Guardamos el id del usuario    
    } else {
        echo "No se ha guardado la imagen";
    }                  
} catch (PDOException $e) {
    // En este caso guardamos los errores en un archivo de errores log
    error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
    // guardamos en ·errores el error que queremos mostrar a los usuarios
    $errores['datos'] = "Ha habido un error <br>";
}


?>