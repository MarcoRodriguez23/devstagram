<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DevsTagram - @yield('titulo')</title>
        @stack('styles')
        <link rel="stylesheet" href="{{ asset('css/app.css')}}">
        <script src="{{ asset('js/app.js')}}" defer></script>
        @livewireStyles
    </head>
    <body class="bg-gray-100">
        <header class="p-5 border-b bg-white shadow">
            <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
                <a href="{{route('home')}}">
                    <h1 class="text-2xl md:text-3xl font-black">
                        Devstagram
                    </h1>
                </a>

                @auth
                    <div class="mt-4 md:mt-0 p-2 mx-2">
                        <form method="GET" action="{{route('lista.show')}}" class="flex gap-10 justify-center">
                            @csrf
                                <div class="flex flex-col items-center">
                                    <label 
                                        class="block mb-1 text-xs text-gray-700 font-bold "
                                        for="usuario">Buscar usuario
                                    </label>
                                    <input 
                                        id="usuario"
                                        type="text"
                                        placeholder="ej. mrtrouble-23"
                                        class="rounded-md shadow-sm border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs p-2 text-center"
                                        name="usuario"
                                    />
                                </div>
                                <input 
                                    type="submit"
                                    class="bg-slate-500 hover:bg-slate-700 transition-colors text-white text-xs font-bold rounded cursor-pointer px-2"
                                    value="Buscar"
                                />
                        </form>
                    </div>
                    <nav class="flex gap-2 items-center mt-4 md:mt-0">
                        <a class="flex items-center gap-2 bg-white border p-2 text-gray-600 rounded text-sm uppercase font-bold cursor-pointer" href="{{route('posts.create')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Crear
                        </a>
                        <a 
                            href="{{route('posts.index',auth()->user()->username)}}" 
                            class="font-bold text-gray-600 text-sm mx-1"
                        >
                            Hola: <span class="font-normal">{{auth()->user()->username}}</span>
                        </a>

                        <form method="POST" action="{{route('logout')}}">
                            @csrf
                            <button type="submit" class="font-bold uppercase text-gray-600 text-sm mx-1">
                                Cerrar Sesión
                            </button>
                        </form>

                    </nav>
                @endauth
                    
                @guest
                    <nav class="flex gap-4 items-center">
                        <a 
                            href="{{route('login')}}" 
                            class="font-bold uppercase text-gray-600 text-sm mx-1"
                        
                        >
                            Login
                        </a>
                        <a 
                            href="{{route('register')}}" 
                            class="font-bold uppercase text-gray-600 text-sm mx-1"
                        >
                            Crear Cuenta
                        </a>
                    </nav>
                @endguest
                
            </div>
        </header>


        <main class="container mx-auto mt-10">
            <h2 class="text-center text-3xl font-black mb-10">@yield('titulo')</h2>
            @yield('contenido')
        </main>

        <footer class="text-center p-5 text-gray-500 font-bold uppercase mt-10">
            DevStagram - Todos los derechos reservados {{now()->year}}
        </footer>

        @livewireScripts
    </body>
</html>