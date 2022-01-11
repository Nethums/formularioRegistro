## Formulario de registro
-------------
Ejercicio de la asignatura Desarrollo de Aplicaciones en Entorno Servidor del Ciclo Superior Semipresencial 2021/22 en el IES Abastos.

Requisito indispensable hacerlo todo por método GET.

### Tabla de contenidos
-------------
1. [Enunciado del ejercicio](#enunciado) 
2. [Funcionamiento básico](#funcionamientoBasico) 
3. [Crear base de datos](#crearBD) 
4. [Qué funcionalidades tiene el ejercicio](#funcionalidades) 
5. [Librerías generales](#libreriasGenerales) 
    - 5.1 [config.php](#config) 
    - 5.2 [bGeneral.php](#bGeneral) 
    - 5.3 [bConecta.php](#bConecta) 
6. [Formulario login](#login) 
   * 6.1 [loginProcesar.php](#loginProcesar)
7. [Formulario de registro](#formularioRegistro) 
    * 7.1 [registroProcesar.php](#registroProcesar)
8. [Formulario para subir imágenes](#subirImagnesphp)
    * 8.1 [subirImagenes.php](#subirImagenesLib)
9. [galeria.php](#galerias)
    * 9.1 [Galería privada](#galeriaPrivada)
    * 9.2 [Galería pública](#galeriaPublica)



<a name="enunciado"/></a>
### **1. Enunciado del ejercicio**
-------------
Los requisitos del ejercicio se encuentran dentro del documento "enunciado.pdf".

<a name="funcionamientoBasico"/></a>
### **2. Funcionamiento básico**
-------------
Se trata de un formulario de registro y login conectados a una base de datos. El usuario se podrá registrar y loguear mediante su usuario y contraseña. También puede subir imágenes y visualizarlas en las diferentes galerías.

Todos los formularios se encuentran en la carpeta "forms", de esta forma quedan separados del código HTML y podemos acceder a ellos más fácilmente.

<a name="crearBD"/></a>
### **3. Crear base de datos**
-------------
Antes de ejecutar el ejercicio hay que crear la base de datos donde se guardará toda la información de los distintos usuarios. 

1. Debemos iniciar el servidor en local con XAMPP o una herramienta similar que nos proporcione un servidor local.

2. Ejecutar el fichero php "baseDatos/crearBD.php". Este fichero contiene la información necesaria para crear la base de datos en local.

3. Si deseamos cambiar el nombre, usuario root y password debemos dirigirnos a "libs/config.php".

4. Dentro del fichero "libs/config.php" también podremos elegir las aficiones que podrá elegir el usuario en el formulario de registro.


<a name="funcionalidades"/></a>
### **4. Qué funcionalidades tiene el ejercicio**
-------------
1. Posibilidad de registro de usuarios.
2. Login con comprobación de datos introducidos.
3. Formulario para subir imágenes a la carpeta pública o privada.
4. Página de galería privada.
5. Página de galería pública.
6. Cada usuario al registrarse dispondrá de una carpeta privada en "imagenes/nombreUsuario/" donde se guardarán las fotos que elija como privadas.

<a name="libreriasGenerales"/></a>
### **5. Librerías generales**
-------------
En este ejercicio se han usado principalmente 3 librerías donde se guarda información útil:

1. [config.php](#config) 
2. [bGeneral.php](#bGeneral) 
3. [bConecta.php](#bConecta) 

<a name="config"/></a>
### **5.1 config.php**
-------------

Dentro de este archivo encontramos todas las variables que se necesitan para modificar el ejercicio. Estas son:

- **$db_hostname**: En este caso localhost.
- **$db_nombre**: Nombre de la base de datos.
- **$db_usuario**: Usuario root.*
- **$db_clave**: Contraseña del root.
- **$directorioUSuarios**: El donde se guardarán las imágenes de forma pública y los directorios privados.
- **$directorioFotosPerfil**: Ruta donde se guardan las fotos de perfil de cada usuario con su nombre.
- **$extensionesValidas**: Las extensiones válidas para las imágenes. Array tipo MIME.
- **$aficionesLista**: Array con las aficiones. Este array se usa para crear la tabla de aficiones y generar el checkbox en el formulario de registro.

***\*** Al tratarse de un ejercicio de forma local el usuario es root y no tiene contraseña.*

<a name="bGeneral"/></a>
### **5.2 bGeneral.php**
-------------

Librería principal donde residen las funciones que se usan en todo el ejercicio. Cada función tiene su explicación comentada. Se ha intentado que sean reutilizables.

<a name="bConecta"/></a>
### **5.3 bConecta.php**
-------------

Fichero que utilizamos para realizar una nueva conexión a la base de datos con los parámetros que teníamos guardados en "libs/config.php".

<a name="login"/></a>
### **6. Formulario login**
-------------
Después de haber creado la base de datos, accederemos al login que se encuentra en "index.php". Aquí veremos un formulario simple donde introducir 2 valores: usuario y password.

Si el acceso es correcto se redirigirá al usuario a la página "pages/eleccionUsuario.php" donde se le dará a elegir entre:

1. Subir imágenes nuevas.
2. Ver su galería privada.
3. Ver su galería pública.

En caso de que el acceso sea incorrecto se mostrará un mensaje de error.

Debajo del formulario hay un enlace para que un nuevo usuario pueda registrarse.

<a name="loginProcesar"/></a>
### **6.1 loginProcesar.php**
-------------
Fichero donde usamos "libs/bConecta.php" para conectarnos a la base de datos y hacer una query donde los parámetros es el usuario y password.

Primero preparamos la consulta y si la query da como resultado 1 fila es porque ha sido correcta y dejamos loguearse al usuario. Si no da resultado es porque el usuario no existe y mostramos un mensaje de error.

<a name="formularioRegistro"/></a>
### 7. Formulario de registro
-------------
Esta página la podemos encontrar en "pages/registro.php" e incluye el formulario "forms/formularioRegistro.php".

Se muestra un formulario de registro donde el usuario podrá introducir sus datos personales así como el nombre de usuario y contraseña que usará para acceder.

También puede añadir una foto de perfil propia que se guardará dentro de la carpeta "fotosPerfil/" con el nombre del usuario.

<a name="registroProcesar"/></a>
### 7.1 registroProcesar.php
-------------
En este archivo de php es donde pasamos a procesar los datos que ha introducido el usuario.

1. Recogemos los datos del formulario.
2. Validamos que sean correctos según los filtros necesarios.
3. Realizamos las acciones correspondientes.

Una vez que hemos recogido y validado los datos del usuario procedemos a validar la imagen de perfil. En este ejercicio se requería una foto de perfil obligatoria, si el usuario no sube ninguna le asignamos la foto por defecto de la carpeta "img/".

Después conectamos con la base de datos y hacemos el INSERT de los datos personales en la tabla "usuarios" y las aficiones en la tabla "aficionesUser". La tabla "aficiones" hace de enlace entre usuarios y aficionesUser para saber qué aficiones ha marcado el usuario.

Por último creamos un directorio con el nombre del usuario dentro de la carpeta "imagenes/" donde irán guardadas las imágenes que el usuario decida subir como privadas.

Si todo ha ido correctamente se le redirigirá a la página "index.php" con un mensaje diciendo que se ha registrado correctamente y ya puede acceder. En caso de error, se redirigirá a la página "pages/registro.php" donde se mostrarán los errores que han habido a la hora del registro debajo del formulario.

<a name="subirImagnesphp"/></a>
### 8. Formulario para subir imágenes
-------------
Formulario donde el usuario podrá subir una imagen cada vez añadiendo una breve descripción (opcional) de la imagen. Existe la posibilidad de subir la imagen como:

- **Privada**: Se guardará dentro del directorio privado del usuario en "imagenes/".
- **Pública**: Se guardará dentro de la carpeta "imagenes/" junto con el resto de imágenes públicas de otros usuarios.


<a name="subirImagenesLib"/></a>
### 8.1 subirImagenes.php
-------------
Debemos procesar los datos igual que hicimos en el <a name="registroProcesar"/>procesamiento del formulario de registro</a>.

1- Recogemos la opción elegida para guardar la imagen privada/pública,
2- Validamos la imagen.
3- Almacenamos la descripción.

Privada
-------------

Después tenemos que averiguar la ID del usuario que hemos recogido por el $_GET ya que la necesitamos para guardar la información dentro de la tabla imagenes.

Es importante quitar los espacios del nombre de la foto para guardarla correctamente y mostrarla posteriormente en la galeria. Para ello usamos la función "reemplazarEnFiles()".

Guardamos en un array todos los nombres de las imágenes que hay en momento y si el usuario sube una imagen que tiene el mismo nombre que una imagen dentro de la carpeta le añadimos el time() para que no haya duplicidades gracias a la función "cambiarNombreFotoSiEstaEnDirectorio()". 

La función "cambiarNombreFotoSiEstaEnDirectorio()" la podemos usar tanto para una imagen pública como para una imagen privada. El funcionamiento es simple, si encuentra el nombre de la foto le añade "time()" entre el nombre y la extensión.

Subimos la imagen mediante la función "cSubirImagen()" y preparamos la consulta INSERT.

Si la consulta ha ido bien volvemos a mostrar el formulario de subirImagens.php con un mensaje diciendo que se ha subido correctamente la imagen.

Pública
-------------

En caso de que la imagen sea pública tenemos que cambiar el directorio, ya que se encuentra dentro de una carpeta con imágenes de otros usuarios.


<a name="galerias"/></a>
### 9. galeria.php
-------------
En esta página el usuario podrá ver sus imágenes privadas y públicas. Podrá navegar gracias a los botones inferiores e incluso acceder a la página para subir otras imágenes.

<a name="galeriaPrivada"/></a>
### 9.1 Galería privada
-------------
Para mostrar las imágenes privadas del usuario basta con escanear el directorio privado y mostrar las imágenes.

<a name="galeriaPublica"/></a>
### 9.2 Galería pública
-------------
Con la galería pública del usuario debemos hacer unos pasos previos ya que las imágenes estarán en una carpeta compartida con otros usuarios y sólo debemos mostrar las imánes del usuario.

Para ello, primero conseguimos su ID con una query simple. Después hacemos una consulta con la ID del usuario y nos devolverá un array con todas las imágenes de ese usuario, tanto públicas como privadas. Tenemos que filtrar ese array y lo hacemos eliminando los elementos que contengan el nombre del usuario entre barras /, ya que eso quiere decir que estarán dentro de la carpeta privada. Por último sólo tenemos que mostrarlas.
