<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar archivos de la convocatoria') }}
        </h2>
        <link href="{{ asset('vendor/lightbox2-2.11.5/dist/css/lightbox.min.css') }}" rel="stylesheet" />
    </x-slot>
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
    </style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('script'))
        {!! session('script') !!}
        @php
            session()->forget('script'); // Eliminar el mensaje después de mostrarlo
        @endphp
    @endif

    @if ($errors->any())
<script>
    let errorMessages = `
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">• {{ $error }}</li>
                        @endforeach
                    </ul>
                `;

    Swal.fire({
                title: 'Espera...',
                html: errorMessages,
                icon: 'error',
                position: 'top-end', // Coloca la alerta en la esquina superior derecha
                showConfirmButton: false, // Oculta el botón de 'OK'
                timer: 3000,
                timerProgressBar: true,
                backdrop: false, // No oscurece la pantalla
                allowOutsideClick: true,
                customClass: {
                    popup: 'swal-popup', 
                    title: 'swal-title', 
                    text: 'swal-text',
                },
            });
</script>
@endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    
                    <h3 class="text-lg font-semibold">Archivos actuales</h3>
                    <ul id="archivoLista" class="mt-4 space-y-4">
                        @foreach ($documentos_convocatoria as $documento)
                            <li class="flex flex-wrap items-center justify-between gap-4 border-b pb-2">
                                @php
                                    $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                    $extension = pathinfo($documento->archivo, PATHINFO_EXTENSION);
                                @endphp
    
                                @if(in_array(strtolower($extension), $extensionesImagen))
                                    <!-- Si es una imagen, la mostramos -->
                                    <div class="w-20 h-20 flex items-center justify-center rounded-md border">
                                        <a href="{{ asset('documentacion_convocatorias/'.$documento->archivo) }}" data-lightbox="documento">
                                            <img src="{{ asset('documentacion_convocatorias/'.$documento->archivo) }}" alt="Documento" class="max-w-full h-auto">
                                        </a>
                                    </div>
                                @else
                                    <div class="w-20 h-20 flex items-center justify-center rounded-md border">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </div>
                                @endif
                                
                                <span class="truncate max-w-xs">{{ $documento->archivo }}</span>
    
                                <div class="flex flex-wrap gap-2">
                                    <input type="file" class="archivoInput hidden" data-id="{{ $documento->id }}">
                                    <button class="bg-yellow-500 text-white px-3 py-1 rounded-md archivoActualizar w-full sm:w-auto" data-id="{{ $documento->id }}">Actualizar</button>
    
                                    <button class="bg-red-500 text-white px-3 py-1 rounded-md archivoEliminar w-full sm:w-auto" data-id="{{ $documento->id }}">Eliminar</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
    
                    <h3 class="text-lg font-semibold mt-6">Agregar nuevos archivos</h3>
                    <form id="uploadForm" action="{{ route('documentacion_convocatoria.store2') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <input type="hidden" name="id_convocatoria" value="{{ $convocatoria->id }}">
                    
                        <label for="archivo" class="block text-lg font-medium text-gray-700">Seleccionar imágenes:</label>
                        <input type="file" id="archivo" name="archivo[]" accept="image/*,application/pdf" multiple 
                            class="mt-2 p-2 border rounded-md w-full">
                    
                        <!-- Contenedor para mostrar las imágenes seleccionadas -->
                        <div id="preview" class="mt-4 flex flex-wrap gap-2"></div>
                    
                        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 w-full sm:w-auto">
                            Subir Imágenes
                        </button>
                    </form>
    
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/lightbox2-2.11.5/dist/js/lightbox-plus-jquery.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    // ✅ ACTUALIZAR ARCHIVO
    document.querySelectorAll(".archivoActualizar").forEach(button => {
        button.addEventListener("click", function () {
            let id = this.getAttribute("data-id");
            let input = document.querySelector(`.archivoInput[data-id="${id}"]`);
            input.click();

            input.addEventListener("change", function () {
                let formData = new FormData();
                formData.append("archivo", input.files[0]);
                formData.append("_method", "PUT"); // Laravel espera un PUT

                let url = `/convocatorias/subir_archivos/update/${id}`;

                fetch(url, {
                    method: "POST", // Enviar como POST con _method: "PUT"
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        //alert("Archivo actualizado correctamente.");
                        Swal.fire({
                            title: 'Se ha actualizado correctamente.',
                            text: 'Archivo actualizado correctamente.',
                            icon: 'success',
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
                        });
                        location.reload();
                    } else {
                        //alert("Error al actualizar archivo.");
                        Swal.fire({
                            title: 'Error...',
                            text: 'Error al actualizar archivo.',
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
                        });
                        console.error("Error:", data);
                    }
                })
                .catch(error => console.error("Error en la solicitud:", error));
            });
        });
    });

    // ✅ ELIMINAR ARCHIVO
    document.querySelectorAll(".archivoEliminar").forEach(button => {
        button.addEventListener("click", function () {
            let id = this.getAttribute("data-id");
            let url = `/convocatorias/subir_archivos/delete/${id}`;

            Swal.fire({
    title: '¿Estás seguro de eliminar este archivo?',
    text: '¡Esta acción no se puede deshacer!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    reverseButtons: true,
    customClass: {
        popup: 'swal-popup',
        title: 'swal-title',
        text: 'swal-text',
    }
}).then((result) => {
    if (result.isConfirmed) {
        let formData = new FormData();
        formData.append("_method", "DELETE");

        fetch(url, {
            method: "POST", // Usamos POST con _method: "DELETE"
            body: formData,
            headers: {
                "X-CSRF-TOKEN": csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Se ha eliminado correctamente.',
                    text: 'Archivo eliminado correctamente.',
                    icon: 'success',
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
                });
                location.reload();
            } else {
                Swal.fire({
                    title: 'Error...',
                    text: 'Surgió un error durante el proceso de borrado del archivo.',
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
                });
                console.error("Error:", data);
            }
        })
        .catch(error => console.error("Error en la solicitud:", error));
    }
});
        });
    });
});
    </script>
    <script>
        document.getElementById('archivo').addEventListener('change', function(event) {
    let previewContainer = document.getElementById('preview');
    previewContainer.innerHTML = ''; // Limpiar previas selecciones

    Array.from(event.target.files).forEach(file => {
        let fileType = file.type;
        let reader = new FileReader();

        if (fileType.includes("image")) {
            // Si es imagen, mostrar previsualización
            reader.onload = function(e) {
                let imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                imgElement.className = "w-20 h-20 object-cover rounded-md border";
                previewContainer.appendChild(imgElement);
            };
            reader.readAsDataURL(file);
        } else if (fileType === "application/pdf") {
            // Si es PDF, mostrar ícono de archivo
            let pdfIcon = document.createElement('div');
            pdfIcon.innerHTML = `
            <div class="w-20 h-20 flex items-center justify-center rounded-md border">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-red-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            `;
            previewContainer.appendChild(pdfIcon);
        }
    });
});
        </script>
</x-app-layout>