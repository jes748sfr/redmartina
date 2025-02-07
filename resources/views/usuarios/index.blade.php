<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>
    <style>
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

            #btn_agregar:hover .text-content {
                opacity: 1;
            }

            @media (max-width: 640px) {
                #btn_agregar:hover {
                    justify-content: center;
                }
            }

            #btn_agregar:hover #btn_icono{
                display: none;
            }
    </style>
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
                <div class="p-6 text-gray-900">    
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-white dark:text-black border border-gray-300">
                            <thead class="text-xs text-white uppercase bg-black dark:bg-gray-50 dark:text-black border-b border-gray-300">
                                <tr>
                                    <th scope="col" class="px-6 py-3 p-2 border-r border-gray-300">
                                        <div class="text-center">Nombre</div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 p-2 border-r border-gray-300">
                                        <div class="text-center">Correo</div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 p-2 border-r border-gray-300">
                                        <div class="text-center">Tipo</div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 p-2 border-r border-gray-300">
                                        <div class="text-center">Fecha de creación</div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 p-2">
                                        <div class="text-center">Estado</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="space-y-2">
                                @foreach ($usuarios as $usuario)
                                    <tr class="bg-black border-b dark:bg-gray-50 dark:border-gray-200 border-gray-800">
                                        <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap dark:text-gray-900 p-2 border-r border-gray-300">
                                            <div class="text-center">{{ $usuario->name }}</div>
                                        </th>
                                        <td class="px-6 py-4 p-2 border-r border-gray-300">
                                            <div class="text-center">{{ $usuario->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 p-2 border-r border-gray-300">
                                            <div class="text-center">{{ $usuario->roles->pluck('name')->implode(', ') }}</div>
                                        </td>
                                        <td class="px-6 py-4 p-2 border-r border-gray-300">
                                            <div class="text-center">{{ $usuario->created_at->format('y-m-d') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center p-2">
                                            <button class="toggle-status flex items-center justify-center mx-auto" 
                                                data-id="{{ $usuario->id }}" 
                                                data-status="{{ $usuario->deleted_at ? 'offline' : 'online' }}">
                                                @if( $usuario->deleted_at )
                                                    <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div> Inhabilitado
                                                @else
                                                    <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> Habilitado
                                                @endif
                                            </button>
                                        </td>                                        
                                    </tr>
                                @endforeach    
                            </tbody>
                        </table>
                        
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-4 right-4">
        <a href="{{ route('usuarios.create') }}" id="btn_agregar" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 overflow-hidden">
            <span id="btn_icono" class="transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </span>
            <span id="btn_texto" style="user-select: none" class="text-content hidden ml-2">Agregar</span>
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    $(document).ready(function() {
        $('.toggle-status').click(function() {
            let button = $(this);
            let userId = button.data('id');

            $.ajax({
                url: `/usuarios/toggle-status/${userId}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'online') {
                        button.html('<div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> Habilitado');

                        Swal.fire({
                                title: '¡Éxito!',
                                text: 'Se ha modificado el estado del usuario.',
                                icon: 'success',
                                position: 'top-end', // Coloca la alerta en la esquina superior derecha
                                showConfirmButton: false, // Oculta el botón de 'OK'
                                timer: 1000, // Desaparece en 1 segundo
                                timerProgressBar: true,
                                backdrop: false, // No oscurece la pantalla
                                allowOutsideClick: true,
                                customClass: {
                                    popup: 'swal-popup', 
                                    title: 'swal-title', 
                                    text: 'swal-text',
                                },
                            }).then(() => {
                            history.replaceState({}, document.title, window.location.pathname); // Limpiar el mensaje de la URL
                            setTimeout(() => {
                                // Borrar el mensaje flash después de la alerta
                                location.reload(); // Recargar la página para que se borre la sesión correctamente
                            }, 1200); // 1.2 segundos después de mostrar el mensaje
                        });
                    } else {
                        button.html('<div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div> Inhabilitado');

                        Swal.fire({
                                title: '¡Éxito!',
                                text: 'Se ha modificado el estado del usuario.',
                                icon: 'success',
                                position: 'top-end', // Coloca la alerta en la esquina superior derecha
                                showConfirmButton: false, // Oculta el botón de 'OK'
                                timer: 1000, // Desaparece en 1 segundo
                                timerProgressBar: true,
                                backdrop: false, // No oscurece la pantalla
                                allowOutsideClick: true,
                                customClass: {
                                    popup: 'swal-popup', 
                                    title: 'swal-title', 
                                    text: 'swal-text',
                                },
                            }).then(() => {
                            history.replaceState({}, document.title, window.location.pathname); // Limpiar el mensaje de la URL
                            setTimeout(() => {
                                // Borrar el mensaje flash después de la alerta
                                location.reload(); // Recargar la página para que se borre la sesión correctamente
                            }, 1200); // 1.2 segundos después de mostrar el mensaje
                        });
                    }
                    button.data('status', response.status);
                },
                error: function(xhr) {

                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un error al modificar el estado del usuario.',
                        icon: 'error',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        backdrop: false,
                        allowOutsideClick: true,
                        customClass: {
                            popup: 'swal-popup', 
                            title: 'swal-title', 
                            text: 'swal-text',
                        },
                    }).then(() => {
                        history.replaceState({}, document.title, window.location.pathname); // Limpiar el mensaje de la URL
                        setTimeout(() => {
                            // Borrar el mensaje flash después de la alerta
                            location.reload(); // Recargar la página para que se borre la sesión correctamente
                        }, 1200); // 1.2 segundos después de mostrar el mensaje
                    });

                    console.error("Error al actualizar estado", xhr.responseText);
                }
            });
        });
    });
</script>
</x-app-layout>
