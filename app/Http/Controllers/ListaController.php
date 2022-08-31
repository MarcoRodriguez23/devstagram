<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ListaController extends Controller
{
    public function __construct(Request $request)
    {
        $this->usuario=$request->usuario;
    }
    public function show()
    {
        $usuarios = User::when($this->usuario,function($query){
            $query->where('username','LIKE',"%".$this->usuario."%");
        })
        ->paginate(12);
        return view('listauser',[
            'usuarios'=>$usuarios
        ]);
    }
}
