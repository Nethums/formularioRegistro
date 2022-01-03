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

                //Recogemos el valor del GET y si el mensaje es error entonces añadimos el echo
                if (isset($_GET['noLogin'])) {   
                    $message = $_GET['noLogin'];
                    if ($message == "error") {
                         echo "<p class=" . 'errorLogin' .">Usuario no encontrado en nuestra base de datos.</p>";
                    }
                }   
                //Recogemos el valor del GET y si el mensaje es ok es que se ha registrado correctamente el usuario y sacamos un mensaje
                if (isset($_GET['registro'])) {   
                    $message = $_GET['registro'];
                    if ($message == "ok") {
                         echo "<p class=" . 'registro' .">Bienvenido. Ya puedes iniciar sesión con tu cuenta.</p>";
                    }
                }
            ?>
            <p>¿Todavía no tienes una cuenta? <a href="pages/registro.php">Regístrate aquí</a>.</p>          
        </div>        
    </body>
</html>