<?php
    require_once("bGeneral.php");
    require_once("config.php");

    $errores = [];

    if (isset($_REQUEST["subirImagen"])) {
        $error = FALSE; 
/*
        echo "<pre>";
        print_r($_REQUEST);
        echo "</pre>";
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
*/
        /* Recogemos los datos del formularioSubirImagenes */
        $opcionSelect = recoge("select");
        $descripcionFotoTextArea = recoge("descripcionFoto");

        /* Comprobamos que los datos sean válidos */
        $opcionesValidas = ["publica", "privada"];
        if (! cSelect("select", $opcionesValidas, $errores)) {
            $error = TRUE;
        }

        if (! cTextarea($descripcionFotoTextArea, "descripcionFoto", $errores, 80, 0)) {
            $error = TRUE;
        }

        if(!$error) {
            /* Depende de la opción que haya elegido el usuario en Select guardaremos la imagen en una carpeta diferente */
            if ($opcionSelect == "privada") {
                $usuario = "user123"; //Prueba donde le doy el usuario directamente
                $path = "usuarios/" . $usuario;
                $fotoUsuario = cSubirImagen("fotoUsuario", $usuario, $directorioUsuarios, $extensionesValidas, $errores);

                if ($fotoUsuario) {
                    echo "Se ha subido la imagen.";
                } else {
                    require("../pages/subirImagenes.php");
                    echo "<p class=" . 'errorImagen' .">No se ha podido subir la imagen. Inténtalo de nuevo.</p>";
                }
            }

            if ($opcionSelect == "publica") {
                echo "Opción pública";
            }

        } else {
            echo "ERROR";
        }

    }


?>