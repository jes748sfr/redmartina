<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subir archivos para actividad martiana') }}
        </h2>
        <link rel="stylesheet" href="@sweetalert2/theme-material-ui/material-ui.css">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="uploadForm" action="{{ route('documentacion_martiana.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_martianas" value="{{ $martiana->id }}">
                    
                        <label for="archivo" class="block text-lg font-medium text-gray-700">Seleccionar imágenes:</label>
                        <input type="file" id="archivo" name="archivo[]" accept="image/*,application/pdf" multiple 
                            class="mt-2 p-2 border rounded-md w-full">
                    
                        <!-- Contenedor para mostrar las imágenes seleccionadas -->
                        <div id="preview" class="mt-4 flex flex-wrap gap-2"></div>
                    
                        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">
                            Subir Imágenes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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