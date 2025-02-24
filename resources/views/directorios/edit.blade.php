<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Directorio') }}
        </h2>
        <link rel="stylesheet" href="@sweetalert2/theme-material-ui/material-ui.css">
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <form action="{{ route('directorios.update', ['id' => $directorio->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" value="{{ $directorio->id }}">
                        
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
                            <textarea id="descripcion" name="descripcion" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Describa la catedra o caracteristicas que desemeña en el area">{{ old('descripcion', $directorio->descripcion) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <select name="pais" id="pais" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/5 border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 px-3 py-2" >
                                <option value="" disabled selected>Seleccione un país</option>
                                @foreach ($paises as $pais)
                                    <option value="{{ $pais['nombre'] }}" {{ $directorio->pais == $pais['nombre'] ? 'selected' : '' }}>{{ $pais['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <select name="nivel" id="nivel" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/5 border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 px-3 py-2" >
                                <option value="" disabled selected>Nivel del directorio</option>
                                    <option value="1" {{ $directorio->nivel == 1 ? 'selected' : '' }}>Nivel 1</option>
                                    <option value="2" {{ $directorio->nivel == 2 ? 'selected' : '' }}>Nivel 2</option>
                                    <option value="3" {{ $directorio->nivel == 3 ? 'selected' : '' }}>Nivel 3</option>
                            </select>
                        </div>

                        @if($directorio->imagen)
                            <label for="imagen" class="block text-lg font-medium text-gray-700">Imagen previa:</label>
                            <img src="{{ asset('img/directorio/'.$directorio->imagen) }}" width="100" alt="Imagen usuario">
                        @else
                            <label for="imagen" class="block text-lg font-medium text-gray-700">Directorio sin imagen:</label>
                            <img src="{{ asset('img/assets/icono.jpg') }}" width="100" alt="Imagen usuario">
                        @endif

                        <label for="imagen" class="block text-lg font-medium text-gray-700">Seleccionar una nueva imagen:</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*" 
                            class="mt-2 p-2 border rounded-md w-full">
                    
                        <!-- Contenedor para mostrar las imágenes seleccionadas -->
                        <div id="preview" class="mt-4 flex flex-wrap gap-2"></div>
                        
                        <button 
                            type="submit"
                            id="submitButton" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 mb-4">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
        <script>
            document.querySelector("form").addEventListener("submit", function(e) {
                e.preventDefault(); // Evita el envío normal del formulario
        
                let formData = new FormData(this);
                let submitButton = document.getElementById("submitButton");
                submitButton.disabled = true; // Desactiva el botón mientras se envía

                let id = formData.get('id');

                let url = "{{ route('directorios.update', ['id' => ':id']) }}".replace(':id', id);
        
                fetch(url, {
                    method: "PUT",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    submitButton.disabled = false; // Reactivar botón
                    document.querySelectorAll(".error-message").forEach(el => el.remove()); // Limpiar errores anteriores
        
                    if (!data.success) {
                        if (data.errors) {
                            for (let field in data.errors) {
                                let input = document.querySelector(`[name="${field}"]`);
                                if (input) {
                                    let errorDiv = document.createElement("div");
                                    errorDiv.className = "error-message text-red-500 text-sm mt-1";
                                    errorDiv.innerText = data.errors[field][0];
                                    input.insertAdjacentElement("afterend", errorDiv);
                                }
                            }
                        }
                    } else {
                        alert("¡Directorio actualizado con éxito!");
                        location.reload(); // Recargar página tras éxito
                    }
                })
                .catch(error => {
                    console.error("Error en la petición:", error);
                    submitButton.disabled = false;
                });
            });
        </script>
</x-app-layout>