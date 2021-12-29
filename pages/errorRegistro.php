<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/estilos.css">
        <title>Registro</title>
    </head>
    <body>       
        <div class="container">
            <h1>Error en el registro</h1>
            <?php 
                /* Recogemos los valores del array $erros enviados por $_GET desde registroProcesar.php. Si el campo tiene un mensaje es que ha habido un error y lo mostramos en la página para que el usuario lo vea */
                
                $errores = $_GET['errores'];
                
                if ($errores[0] != '') { 
                    echo "<p class=" . 'errorLogin' .">El nombre no es válido.</p>";
                }
                if ($errores[1] != '') { 
                    echo "<p class=" . 'errorLogin' .">Los apellidos no son válidos.</p>";
                }                
                if ($errores[2] != '') { 
                    echo "<p class=" . 'errorLogin' .">El usuario no es válido.</p>";
                }
                if ($errores[3] != '') { 
                    echo "<p class=" . 'errorLogin' .">La contraseña no es válida.</p>";
                }
                if ($errores[4] != '') { 
                    echo "<p class=" . 'errorLogin' .">La fecha de nacimiento no es correcta.</p>";
                }
                if ($errores[5] != '') { 
                    echo "<p class=" . 'errorLogin' .">Debes elegir al menos 1 afición.</p>";
                }
            ?>
            <a href="registro.php" class="botonFormulario">Volver al formulario de registro</a>
        </div>        
    </body>
</html>