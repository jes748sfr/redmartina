<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

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
