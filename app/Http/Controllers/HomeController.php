<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // este tipo de función no es necesario de especificar para que sea llamada
    public function __invoke()
    {

        //obtener a quienes seguimos
        //pluck permite extraer una columna en especial en la consulta
        $ids=(auth()->user()->followings->pluck('id')->toArray());
        //usar whereIn permite enviar una arreglo para consulta, en este caso queremos que los ids ubicados en la variable $ids sean cosultados en la columna user_id de la tabla Post
        //WhereIn se veria algo asi: WhereIn('user_id',[1,2,3,5])
        //usar latest mostrara los registros del más actual al mas viejo
        //usar paginate nos permitira paginar del lado del front usando links, esta paginación tendra el tamaño del argumento que pongamos
        $posts = Post::whereIn('user_id',$ids)->latest()->paginate(20);

        return view('home',[
            'posts'=>$posts
        ]);   
    }
}
