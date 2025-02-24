<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::withTrashed()
            ->where('id', '!=', Auth::id()) // Excluye al usuario logueado
            ->with('roles')
            ->get();

        return view("usuarios.index", compact('usuarios'));
    }

    public function toggleStatus($id)
    {
        $usuario = User::withTrashed()->findOrFail($id);

        try {

            if ($usuario->deleted_at) {
                $usuario->deleted_at = null;
                $usuario->save();
                $status = 'online';
            } else {
                $usuario->delete(); // Desactivar usuario (Offline)
                $status = 'offline';
            }

            return response()->json([
                'success' => true,
                'message' => 'Estado del usuario actualizado correctamente.',
                'status' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al modificar el estado del usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        return view("usuarios.create");
    }

    public function store(Request $request)
    {
        $mensajes = [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y un solo espacio entre palabras.',
        
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.lowercase' => 'El correo debe estar en minúsculas.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.max' => 'El correo no puede tener más de 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está en uso.',
        
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.',
            'password.rules' => 'La contraseña no cumple con los requisitos de seguridad.',
        
            'role.required' => 'El rol es obligatorio.',
            'role.string' => 'El rol debe ser una cadena de texto.',
            'role.exists' => 'El rol seleccionado no es válido.'
        ];

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'regex:/^(?!.*\s{2,})[A-Za-zÁÉÍÓÚáéíóúÑñ]+(?:\s[A-Za-zÁÉÍÓÚáéíóúÑñ]+)*$/u'],
            'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults(), 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{8,}$/'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ], $mensajes);

        if ($validator->fails()) {
            return redirect()->route('usuarios.create') // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

        try{

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Asignar el rol
            $user->assignRole($request->role);

            $script = "<script>
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Se ha creado el usuario correctamente.',
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
                        window.location.reload(); // Recargar la página para que se borre la sesión correctamente
                    }, 1200); // 1.2 segundos después de mostrar el mensaje
                });
            </script>";

        return redirect()->route('Ver_usuarios')->with('script', $script);

        } catch (\Exception $e) {

            $script = "<script>
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
                        window.location.reload(); // Recargar la página para que se borre la sesión correctamente
                    }, 1200); // 1.2 segundos después de mostrar el mensaje
                });
            </script>";

            return redirect()->route('Ver_usuarios')->with('script', $script);

        }
    }

}
