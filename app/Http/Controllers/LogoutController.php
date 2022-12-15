<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function store()
    {
        //permite cerrar la sesion actual
        auth()->logout();
        //se le dirige al usuario a la pagina de login
        return redirect()->route('login');
    }
}
