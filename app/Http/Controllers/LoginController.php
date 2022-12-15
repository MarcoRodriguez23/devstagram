<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    //función que se encarga de mostrar el formulario de login
    public function index()
    {
        return view('auth/login');
    }

    //función que se encarga de comparar la información ingresada en el formulario con la BD
    public function store(Request $request)
    {
        //se valida que se llene el email y el password, asi como que el email tenga el formato de email
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        /*
            mediante un if se comprueba que
            si las credenciales de email y password no coinciden, volver a la pagina que envio esta petición con el mensaje de "credenciales incorrectas"
            el $request->remember sirve para mantener la sesión abierta si asi lo pide el usuario
        */
        if(!auth()->attempt($request->only('email','password'), $request->remember)){
            return back()->with('mensaje','Credenciales incorrectas');
        }

        /*
        si el email y la contraseña coinciden, se procede a enviar al usuario a la pagina de posts, junto con el objeto de user para mostrar esta pagina de manera personalizada
        */
        return redirect()->route('posts.index',auth()->user()->username);
    }
}
