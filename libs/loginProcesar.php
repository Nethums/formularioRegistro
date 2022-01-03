
<?php
    include ('bConecta.php');

    $usuario = $_REQUEST['usuarioLogin'];
    $clave = $_REQUEST['contrasenaLogin'];

    $consulta = "SELECT * FROM usuarios 
        WHERE user=:usuario
        AND pass=:clave";
    $result = $pdo->prepare($consulta);
    $result -> execute(array(":usuario" => $usuario, ":clave" => $clave));
    $filasConsulta = $result -> rowCount();  //Contamos las filas devueltas por la consulta $result

    if (!$filasConsulta) {
        //Añadimos un mensaje por GET para recogerlo en la página index.php y mostrar un mensaje de error en caso de que la identificación haya sido
        header('Location: ../index.php?noLogin=error');
    } else {
        header('Location: ../pages/eleccionUsuario.php?usuario=' . $usuario);
    }

?>