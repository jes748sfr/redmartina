<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        #carousel {
    position: relative;
    z-index: 1;
}

.activity-card {
    position: relative; /* Asegúrate de que la tarjeta esté en su contenedor */
    z-index: 0;
}

.prev-btn, .next-btn {
    z-index: 10; /* Asegúrate de que los botones estén por encima de las tarjetas */
}

.activity-card {
    position: absolute;
    width: 100%;
    opacity: 0;
    transform: translateX(100%);
    transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
}

.activity-card.active {
    opacity: 1;
    transform: translateX(0);
}

.activity-card.prev {
    transform: translateX(-100%);
}

#carousel {
    position: relative;
    width: 100%;
}

#carousel-inner {
    display: flex;
    justify-content: center;
    overflow: hidden;
}



    </style>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-row md:flex">
                    <!-- Versión Escritorio -->
                    <div class="hidden md:flex flex-row w-full">
                        <div class="basis-2/3">
                            <div class="p-6 text-gray-900 text-xl text-center font-bold">
                                {{ __("Actividades:") }}
                            </div>
                            <div id="carousel" class="relative w-full ml-5 mb-5">
                                <div id="carousel-inner">
                                    <div class="relative w-[576px] h-[193.6px] flex flex-col items-center bg-white-50 border border-blue-200 rounded-lg shadow-sm md:flex-row hover:bg-blue-100 transition">
                                        <!-- Botón izquierdo (Prev) -->
                                        <button class="prev-btn absolute left-2 top-1/2 w-10 h-10 bg-blue-500 text-white flex items-center justify-center rounded-full hover:bg-blue-400 transform -translate-y-1/2">
                                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9 1 5l4-4"></path>
                                            </svg>
                                        </button>
                                        @foreach ($actividades as $index => $actividad)
                                            <a href="{{ route('editar_Actividad', ['id' => $actividad->id]) }}" 
                                                class="flex flex-row w-full h-full activity-link activity-card  
                                                    @if($index > 0) hidden @endif"
                                                data-index="{{ $index }}">
                                                @php
                                                    $imagen = $actividad->documentacionAs->isNotEmpty() 
                                                            ? 'documentacion_actividades/' .$actividad->documentacionAs->first()->archivo 
                                                            : 'img/assets/icono.jpg';
                                                    $nombreArchivo = pathinfo($imagen, PATHINFO_FILENAME);
                                                    $extension = pathinfo($imagen, PATHINFO_EXTENSION);
                                                    $imagen = ($extension == 'pdf') 
                                                            ? 'img/assets/icono.jpg' 
                                                            : $imagen;
                                                @endphp
                                                <!-- Imagen -->
                                                <img class="w-48 h-48 object-fill rounded-t-lg md:w-48 md:h-48 md:rounded-none md:rounded-s-lg" src="{{ asset($imagen) }}" alt="">
                                                
                                                <!-- Texto -->
                                                <div class="flex flex-col justify-between p-4 leading-normal">
                                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-900">{{ Str::limit($actividad->titulo, 50, '...') }}</h5>
                                                    <p class="mb-3 font-normal text-blue-800">{!! $actividad->cuerpo_truncado !!}</p>
                                                </div>
                                            </a>
                                        @endforeach
                        
                                        <!-- Botón derecho (Next) -->
                                        <button class="next-btn absolute right-2 top-1/2 w-10 h-10 bg-blue-500 text-white flex items-center justify-center rounded-full hover:bg-blue-400 transform -translate-y-1/2">
                                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"></path>
                                            </svg>
                                        </button>
                                    </div>                    
                                </div>
                            </div>
                        </div>
                        <div class="basis-1/3">
                            <div class="p-6 text-gray-900 text-xl font-bold">
                                {{ __("Noticias:") }}
                            </div>
                            @foreach($noticias as $noticia)
                            <a href="{{ route('editar_Actividad', ['id' => $noticia->id]) }}" 
                                class="flex items-center gap-4 max-w-sm p-6 bg-white border border-blue-50 rounded-lg shadow-sm hover:bg-blue-50">
                                @php
                                    $documentos = $noticia->documentacionAs ?? collect();
                                    $imagen = $documentos->isNotEmpty() 
                                            ? 'documentacion_actividades/' .$documentos->first()->archivo 
                                            : 'img/assets/icono.jpg';
                                    $nombreArchivo = pathinfo($imagen, PATHINFO_FILENAME);
                                    $extension = pathinfo($imagen, PATHINFO_EXTENSION);
                                    $imagen = ($extension == 'pdf') 
                                            ? 'img/assets/icono.jpg' 
                                            : $imagen;
                                @endphp
                                <!-- Imagen -->
                                <img class="w-16 h-16 rounded-lg object-cover" src="{{ asset($imagen) }}" alt="">
                                <!-- Texto -->
                                <h5 class="text-sm tracking-tight text-gray-900">
                                    {{ Str::limit($noticia->titulo, 50, '...') }}
                                </h5>
                            </a>
                            @endforeach
                        </div>
                    </div>
                
                    <!-- Versión Móvil -->
                    <div class="flex flex-col md:hidden w-full">
                        <div class="p-4 text-gray-900 text-xl font-bold">
                            {{ __("Actividades:") }}
                        </div>
                        <div id="carousel-mobile" class="relative w-full">
                            <div class="flex flex-col space-y-4">
                                @foreach ($actividades as $actividad)
                                    <a href="{{ route('editar_Actividad', ['id' => $actividad->id]) }}" 
                                        class="flex flex-col bg-white border border-blue-200 rounded-lg shadow-sm hover:bg-blue-100 transition">
                                        @php
                                            $imagen = $actividad->documentacionAs->isNotEmpty() 
                                                    ? 'documentacion_actividades/' .$actividad->documentacionAs->first()->archivo 
                                                    : 'img/assets/icono.jpg';
                                            $extension = pathinfo($imagen, PATHINFO_EXTENSION);
                                            $imagen = ($extension == 'pdf') 
                                                    ? 'img/assets/icono.jpg' 
                                                    : $imagen;
                                        @endphp
                                        <!-- Imagen -->
                                        <img class="w-full h-40 object-cover rounded-t-lg" src="{{ asset($imagen) }}" alt="">
                                        
                                        <!-- Texto -->
                                        <div class="p-4">
                                            <h5 class="mb-2 text-lg font-bold tracking-tight text-blue-900">{{ Str::limit($actividad->titulo, 50, '...') }}</h5>
                                            <p class="text-blue-800">{!! $actividad->cuerpo_truncado !!}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                
                        <div class="p-4 text-gray-900 text-xl font-bold">
                            {{ __("Noticias:") }}
                        </div>
                        <div class="flex flex-col space-y-4">
                            @foreach($noticias as $noticia)
                                <a href="{{ route('editar_Actividad', ['id' => $noticia->id]) }}" 
                                    class="flex flex-row items-center gap-4 p-4 bg-white border border-blue-50 rounded-lg shadow-sm hover:bg-blue-50">
                                    @php
                                        $documentos = $noticia->documentacionAs ?? collect();
                                        $imagen = $documentos->isNotEmpty() 
                                                ? 'documentacion_actividades/' .$documentos->first()->archivo 
                                                : 'img/assets/icono.jpg';
                                        $extension = pathinfo($imagen, PATHINFO_EXTENSION);
                                        $imagen = ($extension == 'pdf') 
                                                ? 'img/assets/icono.jpg' 
                                                : $imagen;
                                    @endphp
                                    <img class="w-14 h-14 rounded-lg object-cover" src="{{ asset($imagen) }}" alt="">
                                    <h5 class="text-sm tracking-tight text-gray-900">
                                        {{ Str::limit($noticia->titulo, 50, '...') }}
                                    </h5>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="p-6 text-gray-900 text-xl font-bold">
                        {{ __("Actividades martianas:") }}
                    </div>
                
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 w-full max-w-6xl">
                        @foreach( $martianas as  $martiana)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-lg">
                                @php
                                    $documentos = $martiana->documentacionMs ?? collect();
                                    $imagen = $documentos->isNotEmpty() 
                                            ? 'documentacion_martianas/' .$documentos->first()->archivo 
                                            : 'img/assets/icono.jpg';
                                    $nombreArchivo = pathinfo($imagen, PATHINFO_FILENAME);
                                    $extension = pathinfo($imagen, PATHINFO_EXTENSION);
                                    $imagen = ($extension == 'pdf') 
                                            ? 'img/assets/icono.jpg' 
                                            : $imagen;
                                @endphp
                                <a href="{{ route('editar_Martiana', ['id' => $martiana->id]) }}" >
                                    <img class="rounded-t-lg w-full h-40 object-cover" src="{{ asset($imagen) }}" alt="" />
                                </a>
                                <div class="p-5">
                                    <a href="{{ route('editar_Martiana', ['id' => $martiana->id]) }}">
                                        <h5 class="mb-2 text-2xl font-bold text-gray-900"> {{ Str::limit($martiana->titulo, 50, '...') }}</h5>
                                    </a>
                                    <p class="mb-3 font-normal text-gray-700">{!! $martiana->cuerpo_truncado !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-col items-center pb-5">
                    <div class="p-6 text-gray-900 text-xl font-bold">
                        {{ __("Convocatorias:") }}
                    </div>
                
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 w-full max-w-6xl">
                        @foreach( $convocatorias as  $convocatoria)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-lg">
                                @php
                                    $documentos = $convocatoria->documentacionCs ?? collect();
                                    $imagen = $documentos->isNotEmpty() 
                                            ? 'documentacion_convocatorias/' .$documentos->first()->archivo 
                                            : 'img/assets/icono.jpg';
                                    $nombreArchivo = pathinfo($imagen, PATHINFO_FILENAME);
                                    $extension = pathinfo($imagen, PATHINFO_EXTENSION);
                                    $imagen = ($extension == 'pdf') 
                                            ? 'img/assets/icono.jpg' 
                                            : $imagen;
                                @endphp
                                <a href="{{ route('editar_Convocatoria', ['id' => $convocatoria->id]) }}" >
                                    <img class="rounded-t-lg w-full h-40 object-cover" src="{{ asset($imagen) }}" alt="" />
                                </a>
                                <div class="p-5">
                                    <a href="{{ route('editar_Convocatoria', ['id' => $convocatoria->id]) }}">
                                        <h5 class="mb-2 text-2xl font-bold text-gray-900"> {{ Str::limit($convocatoria->titulo, 50, '...') }}</h5>
                                    </a>
                                    <p class="mb-3 font-normal text-gray-700">{!! $convocatoria->cuerpo_truncado !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
