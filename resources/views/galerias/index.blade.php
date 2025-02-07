<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Galeria') }}
        </h2>
        <link href="{{ asset('vendor/lightbox2-2.11.5/dist/css/lightbox.min.css') }}" rel="stylesheet" />
        <style>

        .swal-popup {
            @apply bg-white shadow-lg rounded-xl p-6; /* Fondo blanco con sombra y bordes redondeados */
        }

        .swal-title {
            @apply text-2xl font-bold text-gray-800; /* Texto grande y negrita */
        }

        .swal-text {
            @apply text-lg text-gray-600; /* Texto mediano y gris */
        }


            #btn_regresar {
    width: 48px;
}

/* Expansión del botón al pasar el mouse */
#btn_regresar:hover {
    width: 140px; /* Espacio suficiente para el texto */
    background-color: white;
    color: cadetblue;
    border-color: cadetblue;
    border-width: 2px;
}

/* Mostrar texto al hacer hover */
#btn_regresar:hover #btn_regresar_texto {
    display: inline;
}

/* Ocultar ícono al mostrar texto */
#btn_regresar:hover #btn_regresar_icono {
    opacity: 0;
}

/* Transición suave del ícono */
#btn_regresar_icono {
    transition: opacity 0.3s;
}

/* Texto visible al hacer hover */
#btn_regresar:hover .text-content {
    opacity: 1;
}

/* Asegurar que el SVG desaparezca al hacer hover */
#btn_regresar:hover #btn_regresar_icono {
    display: none;
}
            /* Botón inicial con ancho reducido */
            #btn_agregar {
                width: 48px;
            }
        
            /* Expansión del botón al pasar el mouse */
            #btn_agregar:hover {
                width: 140px; /* Espacio suficiente para el texto */
                background-color: white;
                color: cadetblue;
                border-color: cadetblue;
                border-width: 2px;
            }
        
            /* Mostrar texto al hacer hover */
            #btn_agregar:hover #btn_texto {
                display: inline;
            }
        
            /* Ocultar ícono al mostrar texto */
            #btn_agregar:hover #btn_icono {
                opacity: 0;
            }
        
            /* Transición suave del ícono */
            #btn_icono {
                transition: opacity 0.3s;
            }
        
            /* Alinear el texto y mantener estilos */
            .text-content {
                white-space: nowrap; /* Evitar saltos de línea */
                opacity: 0;
                transition: opacity 0.3s;
            }
        
            /* Texto visible al hacer hover */
            #btn_agregar:hover .text-content {
                opacity: 1;
            }

            /* Botón inicial con ancho reducido */
    #btn_buscar {
        width: 48px;
    }

    /* Expansión del botón al pasar el mouse */
    #btn_buscar:hover {
        width: 140px;
        background-color: white;
        color: cadetblue;
        border-color: cadetblue;
        border-width: 2px;
    }

    /* Mostrar texto al hacer hover */
    #btn_buscar:hover #btn_buscar_texto {
        display: inline;
    }

    /* Ocultar ícono al mostrar texto */
    #btn_buscar:hover #btn_buscar_icono {
        opacity: 0;
    }

    /* Transición suave del ícono */
    #btn_buscar_icono {
        transition: opacity 0.3s;
    }

    /* Texto visible al hacer hover */
    #btn_buscar:hover .text-content {
        opacity: 1;
    }

    @media (max-width: 640px) {
    #btn_agregar:hover {
        justify-content: center;
    }

    #btn_buscar:hover {
        justify-content: center;
    }

    #btn_regresar:hover {
        justify-content: center;
    }
}

/* Asegurar que el SVG desaparezca */
    #btn_agregar:hover #btn_icono,
    #btn_buscar:hover #btn_buscar_icono {
        display: none;
    }

    #btn_regresar:hover #btn_regresar_icono{
        display: none;
    }
        </style>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('script'))
        {!! session('script') !!}
        @php
        session()->forget('script'); // Eliminar el mensaje después de mostrarlo
        @endphp
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Formulario de búsqueda -->
                </div> --}}

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @forelse($galerias as $galeria)
                        <div class="max-w-sm h-[350px] min-h-[350px] p-6 bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col justify-between">
                            <div>
                                <a href="{{ route('editar_Galeria', ['id' => $galeria->id]) }}">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 text-center min-h-[64px]">
                                        {{ Str::limit($galeria->titulo, 50, '...') }}
                                    </h5>
                                </a>                      
                                <div class="mb-0">
                                    <div class="relative">
                                        <div class="carousel-container overflow-hidden relative h-[200px]" data-id="{{ $galeria->id }}" data-current-index="0">
                                            <!-- Carrusel de imágenes -->
                                            <div class="carousel-wrapper flex transition-transform duration-700 ease-in-out">
                                                @foreach($galeria->fotos as $foto)
                                                    <div class="carousel-item min-w-full flex-shrink-0 flex justify-center items-center h-[200px]">
                                                        <a href="{{ asset('img/galeria/'.$foto->imagen) }}" data-lightbox="documento{{ $galeria->id }}">
                                                            <img src="{{ asset('img/galeria/'.$foto->imagen) }}" width="100" alt="Imagen {{ $foto->id }}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- Controles del carrusel -->
                                            <button class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-black text-white p-2 rounded-full prevBtn" data-id="{{ $galeria->id }}">
                                                &lt;
                                            </button>
                                            <button class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-black text-white p-2 rounded-full nextBtn" data-id="{{ $galeria->id }}">
                                                &gt;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-auto flex gap-2 pt-3">
                                <a href="{{ route('editar_Galeria', ['id' => $galeria->id]) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-full hover:bg-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>                                      
                                </a>                           
                                <form action="{{ route('galerias.delete', $galeria->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-full hover:bg-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>                                          
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center">
                            <p class="text-gray-500">No se encontraron galerias.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-4 right-4">
        <a href="{{ route('crear_Galeria') }}" id="btn_agregar" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 overflow-hidden">
            <span id="btn_icono" class="transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </span>
            <span id="btn_texto" style="user-select: none" class="text-content hidden ml-2">Agregar</span>
        </a>
    </div>
    <script src="{{ asset('vendor/lightbox2-2.11.5/dist/js/lightbox-plus-jquery.js') }}"></script>
    <script>
        const button = document.getElementById("btn_agregar");
        const icon = document.getElementById("btn_icono");
        const text = document.getElementById("btn_texto");
    
        button.addEventListener("mouseenter", () => {
            icon.classList.add("hidden");
            text.classList.remove("hidden");
        });
    
        button.addEventListener("mouseleave", () => {
            icon.classList.remove("hidden");
            text.classList.add("hidden");
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
    const prevBtns = document.querySelectorAll(".prevBtn");
    const nextBtns = document.querySelectorAll(".nextBtn");

    prevBtns.forEach(prevBtn => {
        prevBtn.addEventListener("click", function() {
            const id = prevBtn.getAttribute("data-id");
            const carouselContainer = document.querySelector(`.carousel-container[data-id="${id}"]`);
            const carouselWrapper = carouselContainer.querySelector(".carousel-wrapper");
            const totalItems = carouselWrapper.querySelectorAll(".carousel-item").length;
            let currentIndex = parseInt(carouselContainer.getAttribute('data-current-index'));

            // Desplazar al índice anterior
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = totalItems - 1;  // Volver al último elemento
            }

            // Actualizar la propiedad de índice
            carouselContainer.setAttribute('data-current-index', currentIndex);

            // Aplicar el desplazamiento
            carouselWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
        });
    });

    nextBtns.forEach(nextBtn => {
        nextBtn.addEventListener("click", function() {
            const id = nextBtn.getAttribute("data-id");
            const carouselContainer = document.querySelector(`.carousel-container[data-id="${id}"]`);
            const carouselWrapper = carouselContainer.querySelector(".carousel-wrapper");
            const totalItems = carouselWrapper.querySelectorAll(".carousel-item").length;
            let currentIndex = parseInt(carouselContainer.getAttribute('data-current-index'));

            // Desplazar al índice siguiente
            if (currentIndex < totalItems - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;  // Volver al primer elemento
            }

            // Actualizar la propiedad de índice
            carouselContainer.setAttribute('data-current-index', currentIndex);

            // Aplicar el desplazamiento
            carouselWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
        });
    });
});
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.9.6/lottie.min.js"></script>
</x-app-layout>