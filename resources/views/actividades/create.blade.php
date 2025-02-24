<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Actividad') }}
        </h2>
        <link rel="stylesheet" href="@sweetalert2/theme-material-ui/material-ui.css">
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
                    <form id="actividadForm" action="{{ route('actividades.store') }}" method="POST">
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
                                class="w-full sm:w-1/2 md:w-1/3 lg:w-1/5 border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 px-3 py-2" 
                                required>
                                <option value="" disabled selected>Selecciona una opción</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="fecha" class="block text-gray-700 font-bold mb-2">Fecha:</label>
                            <input 
                                type="date" 
                                id="fecha" 
                                name="fecha" 
                                class="w-full sm:w-1/2 md:w-1/3 lg:w-1/5 border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 px-3 py-2" 
                                max="<?= date('Y-m-d'); ?>" 
                                required>
                        </div>

                        <input type="hidden" name="agregar_file" id="agregar_file" value="0">

                        <button 
                            type="button" 
                            id="submitButton"
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
    <script>
        document.getElementById('submitButton').addEventListener('click', function (event) {
            event.preventDefault(); // Evita que el formulario se envíe inmediatamente

            var titulo = document.getElementById('titulo').value;
            var fecha = document.getElementById('fecha').value;
            var noticia = document.getElementById('noticia').value;

            if (!titulo || !fecha || !noticia) {
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
                    title: 'Una cosa mas...',
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
                    document.getElementById('agregar_file').value = '1';
                    document.getElementById('actividadForm').submit();
                } else {
                    // Si el usuario cancela, no se hace nada
                    document.getElementById('actividadForm').submit();
                }
            });
        });
    </script>

</x-app-layout>
