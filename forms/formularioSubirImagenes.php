<!--
    Necesitamos saber el usuario para pasarlo por el método GET, por lo que en el formulario insertamos un código PHP para recoger el nombre del usuario que se ha ido pasando por las páginas por el método GET e incluirlo dentro del action
-->

<?php 
    $usuario = $_GET['usuario'];
    echo "<form action='../libs/subirImagenes.php?usuario=".$usuario."' method='post' enctype='multipart/form-data' class='subirFoto'>";
?>

    <label>Indica cómo quieres subir tu imagen al servidor: </label>
    <select name="select">
        <option value="privada">Imagen privada</option>
        <option value="publica">Imagen pública</option>
    </select>
    <textarea name="descripcionFoto" rows="5" cols="50" placeholder="Añade una descripción de la foto (máximo 80 caracteres)." ></textarea>    
    <input type="file" name="fotoUsuario" id="fotoUsuario" />
    <input type="submit" name="subirImagen" value="Subir imagen" />
    <input type="reset" value="Borrar todo" />
</form>