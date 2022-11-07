@extends('layouts.app')
<!--
    usando el @ section con el nombre entre parentesis se llenara el yield que contenga dicho nombre
    en este caso se llena el yield de titulo ubicado en el archivo app.blade.php
-->
@section('titulo')
    Página principal
@endsection

<!--
    usando el @ section con el nombre entre parentesis se llenara el yield que contenga dicho nombre
    en este caso se llena el yield de contenido ubicado en el archivo app.blade.php
-->
@section('contenido')
    
    <x-listar-post :posts="$posts"/>

@endsection