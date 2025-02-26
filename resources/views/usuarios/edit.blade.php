<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar un usuario') }}
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

        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .password-input {
            padding-right: 40px; /* Ajuste el padding para que no se superponga el ícono con el texto */
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
                    <form id="actividadForm" action="{{ route('usuarios.update', ['id' => $usuario->id]) }}" method="POST">
                        @csrf
                        @method('PATCH') 
                        
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-bold mb-2">Nombre del usuario:</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name"
                                value="{{ old('name', $usuario->name) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" 
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-bold mb-2">Correo:</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email"
                                value="{{ old('email', $usuario->email) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" 
                                required>
                        </div>

                        <div class="mb-4 password-wrapper">
                            <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña:</label>
                            <div class="relative">
                                <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 password-input" 
                                value="{{ old('password') }}">
                                <svg id="togglePassword" class="password-toggle" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                              </div>
                        </div>

                        <div class="mb-4 password-wrapper">
                            <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirme su contraseña:</label>
                            <div class="relative">
                                <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 password-input" 
                                value="{{ old('password') }}">
                                <svg id="togglePassword2" class="password-toggle" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-gray-700 font-bold mb-2">Seleccione un rol:</label>
                            <select name="role"  
                                class="w-full sm:w-2/5 md:w-1/3 lg:w-1/5 border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50" 
                                required>
                                <option value="" disabled>Selecciona una opción</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" 
                                        {{ $role->name == $userRole ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <button 
                            type="submit" 
                            id="submitButton"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">
                            Actualizar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            togglePassword.innerHTML = type === 'password' ? 
                `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>` :
                `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off">
                    <path d="M17.94 17.94A10.93 10.93 0 0 1 12 20c-5 0-9.27-3-11-8 1.17-2.91 3.24-5.23 5.52-6.58m3.56-1.52A10.94 10.94 0 0 1 12 4c5 0 9.27 3 11 8-.37.92-.84 1.79-1.39 2.61M1 1l22 22"></path>
                </svg>`;
        });

        const passwordInput2 = document.getElementById('password_confirmation');
        const togglePassword2 = document.getElementById('togglePassword2');

        togglePassword2.addEventListener('click', () => {
            const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput2.setAttribute('type', type);

            togglePassword2.innerHTML = type === 'password' ? 
                `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>` :
                `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off">
                    <path d="M17.94 17.94A10.93 10.93 0 0 1 12 20c-5 0-9.27-3-11-8 1.17-2.91 3.24-5.23 5.52-6.58m3.56-1.52A10.94 10.94 0 0 1 12 4c5 0 9.27 3 11 8-.37.92-.84 1.79-1.39 2.61M1 1l22 22"></path>
                </svg>`;
        });
    </script>

<script>
    document.getElementById("actividadForm").addEventListener("submit", function(event) {
    const password = document.getElementById("password").value;
    const passwordConfirm = document.getElementById("password_confirmation").value;

    if (password !== passwordConfirm) {
        event.preventDefault(); // Detiene el envío del formulario
        Swal.fire({
            title: 'Error',
            text: 'Las contraseñas no coinciden.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    }
});
</script>

</x-app-layout>
