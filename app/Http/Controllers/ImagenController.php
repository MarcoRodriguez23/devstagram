<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        $imagen = $request->file('file');
        $nombreImagen = Str::uuid(). "." . $imagen->extension();
        $imagenServidor = Image::make($imagen);
        //para recortar
        $imagenServidor->fit(1000,1000);
        //si la carpeta no existe, crearla
        if (!is_dir(public_path('uploads'))) {
            mkdir(public_path('uploads'));
        }
        $imagenPath = public_path('uploads').'/'.$nombreImagen;
        
        $imagenServidor->save($imagenPath);

        return response()->json(['imagen'=> $nombreImagen]);
    }
}
