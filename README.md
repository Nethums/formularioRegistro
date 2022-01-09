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
   * 6.1 [Ficheros necesarios para login](#ficherosNecesariosLogin) 
        * 6.1.1 [loginProcesar.php](#loginProcesar) 
        * 6.1.2 [bGeneral.php](#bGeneral) 
        * 6.1.3 [bConecta.php](#bConecta) 
6. [XXXXXXXXXXXXX](#YYYYYY) 
7. [XXXXXXXXXXXXX](#YYYYYY)
8. [XXXXXXXXXXXXX](#YYYYYY)
9. [XXXXXXXXXXXXX](#YYYYYY)
10. [XXXXXXXXXXXXX](#YYYYYY) 



<a name="enunciado"/></a>
### **1. Enunciado del ejercicio**
-------------
Los requisitos del ejercicio se encuentran dentro del documento "enunciado.pdf".

<a name="funcionamientoBasico"/></a>
### **2. Funcionamiento básico**
-------------
Se trata de un formulario de registro y login conectados a una base de datos. El usuario se podrá registrar y loguear mediante su usuario y contraseña. También puede subir imágenes y visualizarlas en las diferentes galerías.

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

<a name="ficherosNecesariosLogin"/></a>
### **Ficheros necesarios para login**
-------------
* **index.php**
  - Página principal donde se encuentra el formulario.
* **libs/loginProcesar.php**
  - Archivo donde se realizan todas las operaciones necesarias para el login.
* **libs/bConecta.php**
  - Archivo donde se encuentra la conexión de a la base de datos.
* **libs/bGeneral.php**
  - Archivo donde se encuentran las funciones generales que se usan a lo largo del ejercicio.

<a name="loginProcesar"/></a>
### **6.1.1 loginProcesar.php**
-------------
Fichero donde usamos "libs/bConecta.php" para conectarnos a la base de datos y hacer una query donde los parámetros es el usuario y password.

Primero preparamos la consulta y si la query da como resultado 1 fila es porque ha sido correcta y dejamos loguearse al usuario. Si no da resultado es porque el usuario no existe y mostramos un mensaje de error.

<a name="YYYYYYYY"/></a>
### XXXXXXXXXXXXX
-------------
ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ

