<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Actividad') }}
        </h2>
        <link rel="stylesheet" href="@sweetalert2/theme-material-ui/material-ui.css">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('actividades.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="titulo" class="block text-gray-700 font-bold mb-2">Título:</label>
                            <input 
                                type="text" 
                                id="titulo" 
                                name="titulo" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" 
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="cuerpo" class="block text-gray-700 font-bold mb-2">Cuerpo:</label>
                            <textarea 
                                id="cuerpo" 
                                name="cuerpo" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50">
                            </textarea>
                        </div>

                        <div class="mb-4">
                            <label for="noticia" class="block text-gray-700 font-bold mb-2">¿Es una noticia?</label>
                            <select 
                                id="noticia" 
                                name="noticia" 
                                class="w-1/5 border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 " 
                                required>
                                <option value="" disabled selected>Selecciona una opción</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <button 
                            type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">
                            Guardar
                        </button>
                    </form>
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
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</x-app-layout>