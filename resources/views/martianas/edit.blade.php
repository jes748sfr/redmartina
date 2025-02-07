<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Actividad martiana') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('martianas.update', ['id' => $martiana->id]) }}" method="POST">
                        @csrf
                        @method('PUT') 
                        
                        <div class="mb-4">
                            <label for="titulo" class="block text-gray-700 font-bold mb-2">Título:</label>
                            <input 
                                type="text" 
                                id="titulo" 
                                name="titulo" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" 
                                required value="{{ old('titulo', $martiana->titulo) }}">
                        </div>

                        <div class="mb-4">
                            <label for="cuerpo" class="block text-gray-700 font-bold mb-2">Cuerpo:</label>
                            <textarea 
                                id="cuerpo" 
                                name="cuerpo" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50"
                                required>{{ old('cuerpo', $martiana->cuerpo) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="fecha" class="block text-gray-700 font-bold mb-2">fecha:</label>
                            <input 
                                type="date" 
                                id="fecha" 
                                name="fecha" 
                                class="w-1/5 border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" 
                                required value="{{ old('fecha', $martiana->fecha) }}">
                        </div>

                        <button 
                            type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">
                            Guardar
                        </button>
                    </form>
                    @if($documento == true)
                        <a class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600" href="{{ route('documentacion_martiana.edit', ['id' => $martiana->id]) }}">Editar archivos</a>
                    @else
                        <a class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600" href=" {{ route('documentacion_martiana.crear', ['id' => $martiana->id]) }}">Agregar archivos</a>
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
</x-app-layout>