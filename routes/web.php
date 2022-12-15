<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Esta ruta se encarga de mostrar las publicaciones de las personas a las que seguimos
compuesta por:
    controlador:
        donde solo busca los ids de las personas que se siguen y los registros de los posts de esas personas a las que se siguen
    home.blade.php
        layout principal (conformado por todo el HTML del nav, footer y encabezados, asi como de las directivas necesarias para livewire)
        un componente para listar posts el cual es un HTML dentro de un foreach de Laravel
        una relación para conocer a los usuarios que nosotros seguimos user->followings
*/
Route::get('/',HomeController::class)->name('home');

/*
Esta ruta se encarga de mostrar el listado cuando utilizamos el buscador de usuarios
compuesta por:
    controlador:
        constructor
            que se encarga de recibir el request donde se obtiene el nombre del usuario que se esta buscando
        show
            -utiliza la funcion when del modelo User para buscar a los usuarios con cierto nombre, el cual colocamos en la parte del where
            -una vez buscada que si existe una coincidencia el programa envia los usuarios que encontro
    listauser.blade.php
        -extiende el maquetado de layout principal
        -utiliza un if junto con un count para comprobar si existe o no algun usuario para mostrar
        -foreach para ir mostrando todos los usuarios encontrados
        -links para agregar paginacion a la consulta hecha
*/
Route::get('/lista',[ListaController::class,'show'])->name('lista.show');

/*
Esta ruta se encarga de mostrar el formulario para llevar a cabo el registro de un nuevo usuario en el sistema
compuesta por:
    Controlador:
        index
            muestra el formulario correspondiente
        store
            -recibe un request con la información necesaria para llevar a cabo un registro
            -modifica el nombre de usuario para volverlo amigables con las URL
            -valida la información del request mediante los Validate de Laravel
            -se crea la información nueva y se guarda en la base de datos
            -se autentica al usuario
            -se direcciona a la pagina de posts con el objeto de user para mostrar una vista personalizada para cada usuario
    register.blade.php
        -extiende el maquetado de layout principal
        -utiliza grid para dividir el espacio en dos secciones
            la primer sección es una imagen que va a la izquierda
            la segunda sección se trata de un formulario
                -utiliza metodo POST
                -recibre el nombre, username, email, password y -confirmación de password
                -utiliza la directiva de @ error para mostrar los mensajes de error que se lleguen a presentar
                -utiliza la funcion old para mostrar los datos "guardados" en los inputs que pasaron la validación cuando se presenta un campo mal llenado
*/
Route::get('/register', [RegisterController::class,'index'])->name('register');

//este route con post permite enviar el formulario
Route::post('/register', [RegisterController::class,'store'])->name('register');

/*
Esta ruta se encarga de mostrar el formulario para loguearse en el sistema
compuesta por:
    controlador:
        index
            muestra el formulario correspondiente
        store
            -recibe un request en donde viene el email y password del usuario junto con un remember en caso de que el usuario quiera mantener su sesion abierta
            -valida que el campo de email y password hayan sido llenados adecuadamente, sino envia un mensaje de error el cual se genera gracias a los validate de Laravel
            -se comprueba que el email y el password coincidian en la base de datos, sino sucede esto se envia un mensaje de "credenciales incorrectas"
            -en caso de que las credenciales sean las correctas se redirecciona al usuario a la pagina de posts
    login.blade.php
        -extiende el maquetado de layout principal
        -utiliza grid para dividir el espacio en dos secciones
            la primer sección es una imagen que va a la izquierda
            la segunda sección se trata de un formulario
                -utiliza metodo POST
                -recibre el email, password y remember para mantener la sesión abierta
                -utiliza la directiva de @ error para mostrar los mensajes de error que se lleguen a presentar
                -utiliza la funcion old para mostrar los datos "guardados" en los inputs que pasaron la validación cuando se presenta un campo mal llenado
*/
Route::get('/login',[LoginController::class,'index'])->name('login');

//este route con post permite enviar el formulario
Route::post('/login',[LoginController::class,'store'])->name('login');

/*
Esta ruta se encarga de cerrar la sesion
compuesta por
    controlador
        store
            mediante el metodo logout del objeto auth se cierra sesión
            se direccion al usuario a la pagina de login
*/
Route::post('/logout',[LogoutController::class,'store'])->name('logout');

/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        __construct
            comprueba que quien esta autentificado sea el mismo usuario que va a editar el perfil
        index
            muestra el formulario correspondiente
        store
            -recibe un request que estara compuesto por el username, email, foto de perfil y en dado caso de solicitarlo, cambiar la contraseña
            
    index.blade.php
        -el formulario envia una solicitud post
        -contempla los campos de username, correo, utiliza un checkbox para preguntar si se quiere o no cambiar la contraseña, en caso de que si mostrara los campos para nueva contraseña, confirmarla e ingresar la contraseña actual, y por ultimo el campo de imagen donde se podra agregar la imagen de formato jpg, jpeg y png
        
*/
Route::get('/editar-perfil',[PerfilController::class,'index'])->name('perfil.index');
//este route con post permite enviar el formulario
Route::post('/editar-perfil',[PerfilController::class,'store'])->name('perfil.store');

/*
Esta ruta se encarga de mostrar el formulario necesario para crear un nuevo post
compuesta por:
    controlador:
        __construct
            indica mediante un middleware que todas las funciones o acciones solo las puede hacer el autor, a excepción de verlas
        index
            muestra el perfil del usuario con sus publicaciones
        create
            muestra el formulario para poder crear la publicacion
        store
            -recibe un request con la información de la publicación= foto, titulo y descripción
            -se valida cada uno de estos campos mediante los Validate de Laravel
            -se crea la publicación mediante una relación con el usuario
            -se redirecciona a la pagina de posts
        show
            -muestra las publicaciones que uno ha hecho
        destroy
            -mediante el PostPolicy en la funcion delete, se comprueba que quien va a eliminar el post es el mismo usuario que lo creo (el dueño)
            -se elimina el post
            -se ubica la imagen del post en el servidor
            -se elimina
            -se redirecciona al usuario a la pagina de posts
    dashboard.blade.php
        -utilizando route model binding se crea una url para cada usuario
        -en caso de que el dueño del perfil sea quien ingresa a su perfil se le mostraran las opciones de editar perfil
        -en caso de que alguien que no esta logueado pero entre al perfil no podra dar seguir o dejar de seguir
        -mediante el componente de listar posts se muestran todos los posts que ha hecho el perfil que estamos visitando
    show.blade.php
        -vista encargada de mostrar el post de manera individual
        -coloca la imagen del lado izquierdo
        -formulario del lado derecho para agregar comentarios
        
*/
Route::get('/posts/create',[PostController::class,'create'])->name('posts.create'); 
//este route con post permite enviar el formulario
Route::post('/posts',[PostController::class,'store'])->name('posts.store');
/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        index
            
        store
            
    login.blade.php
        
*/
Route::get('/{user:username}/posts/{post}',[PostController::class,'show'])->name('posts.show');
/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        index
            
        store
            
    login.blade.php
        
*/
Route::delete('/posts/{post}',[PostController::class,'destroy'])->name('posts.destroy');
/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        index
            
        store
            
    login.blade.php
        
*/
Route::post('/{user:username}/posts/{post}',[ComentarioController::class,'store'])->name('comentarios.store');
/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        index
            
        store
            
    login.blade.php
        
*/
Route::post('/imagenes',[ImagenController::class,'store'])->name('imagenes.store');

/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        index
            
        store
            
    login.blade.php
        
*/
Route::post('/posts/{post}/likes',[LikeController::class,'store'])->name('posts.likes.store');
/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        index
            
        store
            
    login.blade.php
        
*/
Route::delete('/posts/{post}/likes',[LikeController::class,'destroy'])->name('posts.likes.destroy');

/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        index
            
        store
            
    login.blade.php
        
*/
Route::post('/{user:username}/follow',[FollowerController::class,'store'])->name('users.follow');
/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        index
            
        store
            
    login.blade.php
        
*/
Route::delete('/{user:username}/unfollow',[FollowerController::class,'destroy'])->name('users.unfollow');

/*
Esta ruta se encarga de 
compuesta por:
    controlador:
        index
            
        store
            
    login.blade.php
        
*/
Route::get('/{user:username}',[PostController::class,'index'])->name('posts.index');
