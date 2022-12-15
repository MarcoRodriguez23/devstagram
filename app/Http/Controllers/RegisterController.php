<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index() 
    {
        return view('auth/register');
    }
    public function store(Request $request) 
    {

        /*
        es necesario modificar el username antes de guardarlo en la base de datos debido a que lo haremos amigable con las URL para que aparezca en esta barra
        para ello se utiliza Str::slug que agrega guion medio entre los espacios y lo pasa a minusculas
        asi podemos pasar: MrTrouble 23 a mrtrouble-23
        */
        $request->request->add(['username' => Str::slug($request->username)]);

        /*
        se lleva a cabo la validación de cada elemento, podemos agregar The validations separandolas por un | pero cuando son más de 3 se recomienda entre corchetes y separadas por comas
        */
        $this->validate($request,[
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email'=>'required|unique:users|email|max:60',
            'password'=>'required|confirmed|min:6'
        ]);

        /*
        una vez pasada la validación se crea una nueva instancia de información del modelo User
        */
        User::create([
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            //hasheamos el password
            'password'=>Hash::make($request->password)
        ]);

        // //autenticar un usuario
        // auth()->attempt([
        //     'email'=>$request->email,
        //     'password'=>$request->password
        // ]);
        /*
        otra forma de autentizar al usuario
        al metodo attempt le pasamos un request el cual solo contendra el email y password
        */
        auth()->attempt($request->only('email','password'));

        //redireccionar
        //a redirect le pasamos un route que contendra la vista posts y el objeto de user
        return redirect()->route('posts.index',['user' => auth()->user()->username]);
    }
}
