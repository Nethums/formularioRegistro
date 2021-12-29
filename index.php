<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/estilos.css">
        <title>Inicio</title>
    </head>
    <body>        
        <div class="container">
            <h1>Login/Registro de la web</h1>
            <?php 
                include('forms/formularioLogin.php');
                if (isset($_GET['noLogin'])) {   //Recogemos el valor del GET y si el               mensaje es error entonces añadimos el echo
                    $message = $_GET['noLogin'];
                    if ($message == "error") {
                         echo "<p class=" . 'errorLogin' .">Usuario no encontrado en nuestra base de datos.</p>";
                    }
                }            
            ?>
            <p>¿Todavía no tienes una cuenta? <a href="pages/registro.php">Regístrate aquí</a>.</p>          
        </div>        
    </body>
</html>