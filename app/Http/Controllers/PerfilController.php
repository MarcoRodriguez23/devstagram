<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('perfil.index');
    }
    public function store(Request $request)
    {
        /*
        en caso de querer cambiar el nombre de usuario este se hara más amigable para las URL mediante el Str::slug
        */
        $request->request->add(['username' => Str::slug($request->username)]);
        
        /*
        se valida el username y el email
        la validación más llamativa es la de unique:users,(email/username)
        si queremos editar algun dato de un registro pero que los demás se mantengan necesitamos agregarle al unique:user la columna y el id del registro esto sin importar que campo vayamos a editar
        el validate de not_in indica que no queremos que uno de los siguientes elementos forme parte de las opciones, es como un "puedes escribir lo que quieras menos lo que esta aqui" se coloca el editar-perfil porque al utilizar el - como en el nombre de username esto podria provocar un problema
        asi como existe not_in existe in
        */
        $this->validate($request,[
            'username' => ['required','unique:users,username,'.auth()->user()->id,'min:3','max:20','not_in:editar-perfil'],
            'email'=>['required','unique:users,email,'.auth()->user()->id,'email','max:60']
        ]);

        /*
        -en caso de que el request indique que se va a cambiar la foto de perfil
        -se agrega la imagen a una variable
        -mediante Str::uuid y concatenando la extension se crea un nombre unico para la imagen
        -se crea la imagen utilizando Intervention Image y su metodo make
        -mediante fit podemos recortar la imagen a un tamaño pre definido
        -comprobamos si existe la carpeta para guardar las fotos de perfil, si no existe se crea la carpeta
        -se crea el nombre de la ruta de la imagen y se guarda en la carpeta
        -finalmente se elimina del servidor la antigua imagen que se tenia
        */
        if($request->imagen){
            $imagen = $request->file('imagen');
            $nombreImagen = Str::uuid(). "." . $imagen->extension();
            $imagenServidor = Image::make($imagen);
            //para recortar
            $imagenServidor->fit(1000,1000);
            //si la carpeta no existe, crearla
            if (!is_dir(public_path('perfiles'))) {
                mkdir(public_path('perfiles'));
            }
            $imagenPath = public_path('perfiles').'/'.$nombreImagen;
            
            $imagenServidor->save($imagenPath);
            unlink(public_path('perfiles/'.auth()->user()->imagen));
        }

        //si checkbox = 1 proceder al cambio de contraseña
        if($request->cambiarpassword == 1){
            //verificar que la contraseña actual es la correcta
            if(!$request->passwordA === '' || !$request->password === ''){
                $coincide = password_verify($request->passwordA, auth()->user()->password);
                if($coincide){
                    //validar la nueva contraseña
                    $this->validate($request,[
                        'password'=>'required|confirmed|min:6'
                    ]);
                    //guardar la nueva contraseña
                    $usuario = User::find(auth()->user()->id);
                    $usuario->password = Hash::make($request->password);
                }
                else{
                    return back()->with('mensaje','Contraseña actual incorrecta');
                }
            }
        }

        //guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        //se agrega la nueva direccion de la imagen, pero si no existe se mantiene la anterior, pero si no existe tampoco se queda vacio
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? '';
        $usuario->save();

        return redirect()->route('posts.index',$usuario->username);

    }
}
