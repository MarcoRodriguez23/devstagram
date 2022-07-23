@extends('layouts.app')

@section('titulo')
    Editar perfil: {{auth()->user()->username}}
@endsection

@section('contenido')
    
    <div class="md:flex md:justify-center">
        
        <div class="md:w-1/2 bg-white shadow p-6">
            @if(session('mensaje'))
                <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{session('mensaje')}}</p>
            @endif
            <form class="md:mt-0 mt-10" action="{{route('perfil.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <label for="username" class="mb-2 block uppercase text-gray-500 font-bold">Username</label>
                    <input 
                        id="username"
                        name="username"
                        type="text"
                        placeholder="Tu Username"
                        class="border p-3 w-full rounded-lg @error('username') border-red-500 @enderror"
                        value="{{auth()->user()->username}}"
                    >
                    @error('username')
                        <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="email" class="mb-2 block uppercase text-gray-500 font-bold">Email</label>
                    <input 
                        id="email"
                        name="email"
                        type="email"
                        placeholder="Tu email de registro"
                        class="border p-3 w-full rounded-lg @error('email') border-red-500 @enderror"
                        value="{{auth()->user()->email}}"
                    >
                    @error('email')
                        <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{$message}}</p>
                    @enderror
                </div>

                <div id="checkboxPassword">
                    <label 
                        for="cambiarpassword" 
                        class="mb-2 block uppercase text-gray-500 font-bold"
                    >
                        ¿Desea cambiar su password?
                    </label>
                    <input 
                        type="checkbox" 
                        id="cambiarpassword"
                        value="1"
                        name="cambiarpassword"
                    >
                </div>

                <div id="casillasPassword" class="ocultar">
                    <div class="mb-5">
                        <label for="password" class="mb-2 block uppercase text-gray-500 font-bold">Nuevo Password</label>
                        <input 
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Edita tu password"
                            class="border p-3 w-full rounded-lg @error('password') border-red-500 @enderror"
                        >
                        @error('password')
                            <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{$message}}</p>
                        @enderror
                    </div>
    
                    <div class="mb-5">
                        <label for="password_confirmation" class="mb-2 block uppercase text-gray-500 font-bold">Confirmar password</label>
                        <input 
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="Confirma tu nuevo password"
                            class="border p-3 w-full rounded-lg @error('password') border-red-500 @enderror"
                        >
                    </div>

                    <div class="mb-5">
                        <label for="passwordA" class="mb-2 block uppercase text-gray-500 font-bold">Actual Password</label>
                        <input 
                            id="passwordA"
                            name="passwordA"
                            type="password"
                            placeholder="Password Actual"
                            class="border p-3 w-full rounded-lg @error('passwordA') border-red-500 @enderror"
                        >
                        @error('passwordA')
                            <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label for="imagen" class="mb-2 block uppercase text-gray-500 font-bold">Imágen perfil</label>
                    <input 
                        id="imagen"
                        name="imagen"
                        type="file"
                        class="border p-3 w-full rounded-lg"
                        accept=".jpg, .jpeg, .png"
                    >
                </div>
                <input 
                    type="submit"
                    value="Guardar cambios"
                    class="bg-sky-600 hover:bg-sky-700 text-center transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white"
                >
            </form>
        </div>
    </div>
@endsection