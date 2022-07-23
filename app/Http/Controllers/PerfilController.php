<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

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
        //modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);
        //validando los cambios
        $this->validate($request,[
            'username' => ['required','unique:users,username,'.auth()->user()->id,'min:3','max:20','not_in:editar-perfil'],
            'email'=>['required','unique:users,email,'.auth()->user()->id,'email','max:60']
        ]);

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
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? '';
        $usuario->save();

        return redirect()->route('posts.index',$usuario->username);

    }
}
