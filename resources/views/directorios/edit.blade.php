<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Convocatoria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('directorios.update', ['id' => $directorio->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="area" class="block text-gray-700 font-bold mb-2">Area:</label>
                            <input 
                                type="text" 
                                id="area" 
                                name="area" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" 
                                required value="{{ old('area', $directorio->area) }}">
                        </div>

                        <div class="mb-4">
                            <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre:</label>
                            <input 
                                type="text" 
                                id="nombre" 
                                name="nombre" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" 
                                required value="{{ old('nombre', $directorio->nombre) }}">
                        </div>

                        <div class="mb-4">
                            <label for="correo" class="block text-gray-700 font-bold mb-2">Correo:</label>
                            <input 
                                type="email" 
                                id="correo" 
                                name="correo" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" value="{{ old('correo', $directorio->correo) }}">
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your message</label>
                            <textarea id="descripcion" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Write your thoughts here...">{{ old('descripcion', $directorio->descripcion) }}</textarea>
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
    <script>
        document.getElementById('submitButton').addEventListener('click', function (event) {
            event.preventDefault(); // Evita que el formulario se envíe inmediatamente

            var area = document.getElementById('area').value;
            var nombre = document.getElementById('nombre').value;

            if (!area || !nombre) {
                Swal.fire({
                    title: 'Espera...',
                    text: 'Por favor completa todos los campos requeridos',
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
                //Swal.fire('Error', 'Por favor completa todos los campos requeridos.', 'error');
                return; // Detiene el flujo si algún campo requerido está vacío
            }
            Swal.fire({
                    title: '¿Desea confirmar el nuevo apartado?',
                    text: '¿Quieres agregar fotos o documentos a esta actividad?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No',
                    customClass: {
                        popup: 'swal-popup', 
                        title: 'swal-title', 
                        text: 'swal-text',
                    },
                }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviamos el formulario
                    //document.getElementById('agregar_file').value = '1';
                    document.getElementById('convocatoriaForm').submit();
                } else {
                    // Si el usuario cancela, no se hace nada
                    //document.getElementById('convocatoriaForm').submit();
                    event.preventDefault();
                }
            });
        });
    </script>
    <script>
        document.getElementById('imagen').addEventListener('change', function(event) {
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