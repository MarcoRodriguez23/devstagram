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
        /*
            el when es una función que puede o no devolver una resultado de la base de datos, si no hay alguna coincidencia simplemente no retorna nada
            si encuentra algo en el $this->usuario, se ejecuta la función
            ya dentro podemos utilizar el where para escribir una query
            podemos concatenar cuantos when queramos solo agregando ->when al final del anterior

            NOTA IMPORTANTE
            en ocasiones vamos a querer buscar el mismo termino en diferentes columnas por ejemplo un input ciuidad o CP, aqui ponemos en el primer where ciudad y en el segundo (CP) le ponemos orwhere, esto le dice al programa que si no lo encontro en la primer columna lo busque en la segunda
        */
        $usuarios = User::when($this->usuario,function($query){
            $query->where('username','LIKE',"%".$this->usuario."%");
        })
        ->paginate(12);
        return view('listauser',[
            'usuarios'=>$usuarios
        ]);

        
    }
}
