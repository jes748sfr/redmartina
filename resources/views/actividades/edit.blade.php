<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Actividad') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('vendor/lightbox2-2.11.5/dist/css/lightbox.min.css') }}" rel="stylesheet" />

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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('actividades.update', ['id' => $actividad->id]) }}" method="POST">
                        @csrf
                        @method('PUT') 
                        
                        <div class="mb-4">
                            <label for="titulo" class="block text-gray-700 font-bold mb-2">Título:</label>
                            <input 
                                type="text" 
                                id="titulo" 
                                name="titulo" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" 
                                required value="{{ old('titulo', $actividad->titulo) }}">
                        </div>

                        <div class="mb-4">
                            <label for="cuerpo" class="block text-gray-700 font-bold mb-2">Cuerpo:</label>
                            <textarea 
                                id="cuerpo" 
                                name="cuerpo" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50"
                                required>{{ old('cuerpo', $actividad->cuerpo) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="noticia" class="block text-gray-700 font-bold mb-2">¿Es una noticia?</label>
                            <select 
                                id="noticia" 
                                name="noticia" 
                                class="w-full sm:w-1/2 md:w-1/3 lg:w-1/5 border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 px-3 py-2" 
                                required>
                                    <option value="1" {{ $actividad->noticia == 1 ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ $actividad->noticia == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="fecha" class="block text-gray-700 font-bold mb-2">fecha:</label>
                            <input 
                                type="date" 
                                id="fecha" 
                                name="fecha" 
                                class="w-full sm:w-1/2 md:w-1/3 lg:w-1/5 border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 px-3 py-2"  
                                max="<?= date('Y-m-d'); ?>" 
                                required value="{{ old('fecha', $actividad->fecha) }}">
                        </div>

                        <button 
                            type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 mb-4">
                            Guardar
                        </button>
                    </form>
                    <div class="mb-4">
                        <h3 class="text-gray-700 font-bold mb-2">Archivos adjuntos:</h3>
                        <div class="flex space-x-4 overflow-x-auto p-2 border rounded-md">
                            @foreach ($documentos_actividad as $documento)
                                @php
                                    $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                    $extension = pathinfo($documento->archivo, PATHINFO_EXTENSION);
                                @endphp
                    
                                <div class="relative flex flex-col items-center justify-center">
                                    @if(in_array(strtolower($extension), $extensionesImagen))
                                        <!-- Imagen -->
                                        <a href="{{ asset('documentacion_actividades/'.$documento->archivo) }}" data-lightbox="documento">
                                            <img src="{{ asset('documentacion_actividades/'.$documento->archivo) }}" alt="Documento" class="w-32 h-32 object-cover rounded-md shadow-md">
                                        </a>
                                    @else
                                        <!-- Icono si no es imagen -->
                                        <div class="w-32 h-32 flex items-center justify-center rounded-md border bg-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    
                    @if($documento == true)
                        <a class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600" href="{{ route('documentacion_actividad.edit', ['id' => $actividad->id]) }}">Editar archivos</a>
                    @else
                        <a class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600" href=" {{ route('documentacion_actividad.crear', ['id' => $actividad->id]) }}">Agregar archivos</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#cuerpo', // Selector del textarea
            plugins: 'link image media table codesample fullscreen',
            toolbar: 'undo redo | styleselect | bold italic | link image media | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table codesample fullscreen',
            height: 300,
            menubar: false,
            branding: false,
            automatic_uploads: true,
            /* images_upload_url: '/subir-imagen',
            images_upload_handler: function (blobInfo, success, failure) {
                let formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                fetch('/subir-imagen', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => success(data.location))
                .catch(error => failure('Error al subir la imagen: ' + error.message));
            } */
        });
    </script>
    <script src="{{ asset('vendor/lightbox2-2.11.5/dist/js/lightbox-plus-jquery.js') }}"></script>
</x-app-layout>