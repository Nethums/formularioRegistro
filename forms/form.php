<form action="" method="post" enctype="multipart/form-data">
	<input type="text" name="nombre" placeholder="Nombre" />

	<input type="text" name="apellidos" placeholder="Apellidos" />

    <input type="text" name="usuario" placeholder="Usuario" />

    <input type="text" name="contrasena" placeholder="Contraseña" />
	
    <input type="text" name="localidad" placeholder="Localidad" />

    <input type="text" name="provincia" placeholder="Provincia" />

	<input type="text" name="fechaNacimiento" placeholder="Fecha de nacimiento (dd/mm/aaaa)" />

	<label for="aficiones">Aficiones:</label>
	<div class="opcionesCheck">
		<?php
			generarCheckbox($aficionesLista, "aficiones");
		?>
    </div>
		
	<textarea name="biografia" rows="10" cols="50" placeholder="Escriba aquí cualquier otra cosa relacionada contigo..." ></textarea>
	
	<label for="fotoPerfil">Sube una foto del cartel de la película:</label>
	<input type="file" name="fotoPerfil" id="fotoPerfil" />
	<input type="submit" name="bAceptar" value="Enviar formulario" />
</form>