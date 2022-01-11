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
            <h1 class="registro">Registro de la web</h1>
            <?php 
                include('../forms/formularioRegistro.php');
                echo "<a href='../index.php' class='galeria'>Volver al índice</a>";

                /* Si el usuario no ha podido registrarse lo mandaremos de vuelta a la página de registro pasándole por GET los errores. Si el array errores no está vacío es porque hay algún error y lo mostramos por pantalla */
                
                if ( ! empty($_GET['errores'])) {
                    if ($_GET['errores'][0] != '') { 
                        echo "<p class=" . 'errorLogin' .">El nombre no es válido.</p>";
                    }
                    if ($_GET['errores'][1] != '') { 
                        echo "<p class=" . 'errorLogin' .">Los apellidos no son válidos.</p>";
                    }                
                    if ($_GET['errores'][2] != '') { 
                        echo "<p class=" . 'errorLogin' .">El usuario no es válido.</p>";
                    }
                    if ($_GET['errores'][3] != '') { 
                        echo "<p class=" . 'errorLogin' .">El nombre de usuario ya está registrado, prueba otro.</p>";
                    }
                    if ($_GET['errores'][4] != '') { 
                        echo "<p class=" . 'errorLogin' .">La contraseña no es válida.</p>";
                    }
                    if ($_GET['errores'][5] != '') { 
                        echo "<p class=" . 'errorLogin' .">La fecha de nacimiento no es correcta.</p>";
                    }
                    if ($_GET['errores'][6] != '') { 
                        echo "<p class=" . 'errorLogin' .">Debes elegir al menos 1 afición.</p>";
                    }
                }
                
                
            ?>
        </div>        
    </body>
</html>