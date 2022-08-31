@extends('layouts.app')
@section('titulo')
    Usuarios encontrados
@endsection

@section('contenido')

    @if ($usuarios->count())
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mx-10 xl:mx-0">
            @foreach ($usuarios as $usuario)
                <div class="flex flex-col justify-center items-center p-4">
                    <img src="{{$usuario->imagen ? asset('perfiles').'/'.$usuario->imagen : asset('img/usuario.svg')}}" alt="">
                    <h4 class="my-2 font-bold">{{$usuario->name}}</h4>
                    <a href="{{route('posts.index',$usuario)}}" class="bg-slate-500 hover:bg-slate-700 transition-colors text-white text-sm font-bold rounded cursor-pointer p-2 text-center">Visitar perfil</a>
                </div>
            @endforeach
        </div>
        <div class="my-5 w-11/12 mx-auto">
            {{$usuarios->links()}}
        </div>
    @else
        <h3>No se econtraron usuarios</h3>
    @endif

@endsection