<div>
    <!--usar un count para contar que haya minimo un registro presente-->
    @if ($posts->count())
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mx-10 xl:mx-0 md:px-10">
            <!--mostrando en un ciclo for los posts encontrados-->
            @foreach ($posts as $post)
                    <div>
                        <a href="{{route('posts.show', ['post'=>$post, 'user'=> $post->user])}}">
                            <img src="{{asset('uploads').'/'.$post->imagen}}" alt="{{$post->imagen}}">
                        </a>
                    </div>
            @endforeach
        </div>
        <div class="my-10">
            <!--usar links permite agregar las flechas para la paginación-->
            {{$posts->links()}}
        </div>
    <!--
        este else es en caso de que no existan posts, es decir si no se ha seguido a nadie 
        o si los usuarios a los que seguimos no han publicado nada
    -->
    @else
        <p class="text-gray-600 uppercase text-sm text-center font-bold">No hay posts</p>
    @endif
</div>