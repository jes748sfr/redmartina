<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actividades') }}
        </h2>
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

    @if (session('success'))
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                icon: 'success',
                position: 'top-end', // Coloca la alerta en la esquina superior derecha
                showConfirmButton: false, // Oculta el botón de "OK"
                timer: 1000, // Desaparece en 3 segundos
                timerProgressBar: true,
                backdrop: false, // No oscurece la pantalla
                allowOutsideClick: true,
                customClass: {
                    popup: 'swal-popup', 
                    title: 'swal-title', 
                    text: 'swal-text',
                },
            }).then(() => {
            history.replaceState({}, document.title, window.location.pathname);
        });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error',
                text: "{{ session('error') }}",
                icon: 'error',
                position: 'top-end', // Coloca la alerta en la esquina superior derecha
                showConfirmButton: false, // Oculta el botón de "OK"
                timer: 1000, // Desaparece en 3 segundos
                timerProgressBar: true,
                backdrop: false, // No oscurece la pantalla
                allowOutsideClick: true,
                customClass: {
                    popup: 'swal-popup', 
                    title: 'swal-title', 
                    text: 'swal-text',
                },
            }).then(() => {
            history.replaceState({}, document.title, window.location.pathname);
        });
        </script>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Formulario de búsqueda -->
                    <form method="POST" action="{{ route('buscar_actividad') }}" class="mb-6">
                        @csrf
                        <div class="flex items-center">
                            
                            @if(isset($query))
                                <a href="{{ route('actividades.auth') }}" id="btn_regresar" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center bg-blue-500 hover:bg-blue-600 text-white font-bold mr-4 rounded-full transition-all duration-300 items-center justify-center">
                                    <span id="btn_regresar_icono" class="transition-all duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                        </svg>
                                    </span>
                                    <span id="btn_regresar_texto"  class="text-content hidden ml-2">Regresar</span>
                                </a>
                            @endif

                            <input 
                                type="text" 
                                name="keyword" 
                                value="{{ $query ?? '' }}" 
                                placeholder="Buscar actividades..." 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            >
                            <button type="submit" id="btn_buscar" class="ml-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-full shadow-lg flex items-center justify-center transition-all duration-300 overflow-hidden">
                                <span id="btn_buscar_icono" class="transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </span>
                                <span id="btn_buscar_texto" style="user-select: none" class="text-content hidden ml-2">Buscar</span>
                            </button>
                        </div>
                    </form>
                </div>
                
                @if(isset($query))
                    <div class="p-6">
                        <p class="text-gray-600">Resultados para: <strong>{{ $query }}</strong> ({{ $totalResultados }} encontrados)</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @forelse($actividades as $actividad)
                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col justify-between">
                            <div>
                                <a href="{{ route('editar_Actividad', ['id' => $actividad->id]) }}">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 text-center min-h-[64px]">
                                        {{ Str::limit($actividad->titulo, 50, '...') }}
                                    </h5>
                                </a>
                                <p class="mb-3 font-normal text-gray-700">{!! $actividad->cuerpo_truncado !!}</p>
                            </div>
                            <div class="mt-auto flex gap-2">
                                <a href="{{ route('editar_Actividad', ['id' => $actividad->id]) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-full hover:bg-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>                                      
                                </a>
                                <form action="{{ route('actividades.delete', $actividad->id) }}" method="POST" class="inline">
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
                            <p class="text-gray-500">No se encontraron actividades.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-4 right-4">
        <a href="{{ route('crear_Actividad') }}" id="btn_agregar" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 overflow-hidden">
            <span id="btn_icono" class="transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </span>
            <span id="btn_texto" style="user-select: none" class="text-content hidden ml-2">Agregar</span>
        </a>
    </div>

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
        const buscarButton = document.getElementById("btn_buscar");
        const buscarIcon = document.getElementById("btn_buscar_icono");
        const buscarText = document.getElementById("btn_buscar_texto");
    
        buscarButton.addEventListener("mouseenter", () => {
            buscarIcon.classList.add("hidden");
            buscarText.classList.remove("hidden");
        });
    
        buscarButton.addEventListener("mouseleave", () => {
            buscarIcon.classList.remove("hidden");
            buscarText.classList.add("hidden");
        });
    </script>
    <script>
        const regresarButton = document.getElementById("btn_regresar");
        const regresarIcon = document.getElementById("btn_regresar_icono");
        const regresarText = document.getElementById("btn_regresar_texto");

        regresarButton.addEventListener("mouseenter", () => {
            regresarIcon.classList.add("hidden");
            regresarText.classList.remove("hidden");
        });

        regresarButton.addEventListener("mouseleave", () => {
            regresarIcon.classList.remove("hidden");
            regresarText.classList.add("hidden");
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.9.6/lottie.min.js"></script>
</x-app-layout>