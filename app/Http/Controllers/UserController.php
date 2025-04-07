<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

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
            session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Hubo un error al crear el usuario');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('usuarios.create')
                                 ->withErrors($validator)
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

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha creado el usuario correctamente!');
            return redirect()->route('Ver_usuarios');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al crear el usuario');
            return redirect()->route('Ver_usuarios');
        }
    }

    public function edit($id)
    {
        $usu = User::find(Auth::id());
        // Verificar si el ID del usuario autenticado es igual al que intenta editar
        if ($usu->id == $id) {
            return redirect()->route('Ver_usuarios');
        }

        $usuario = User::withTrashed()->findOrFail($id);
        $roles = Role::all(); // Obtener todos los roles
        $userRole = $usuario->roles->first()->name ?? ''; // Obtener el rol asignado al usuario

        return view('usuarios.edit', compact('usuario', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
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

        'password.confirmed' => 'Las contraseñas no coinciden.',
        'password.regex' => 'La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.',

        'role.required' => 'El rol es obligatorio.',
        'role.string' => 'El rol debe ser una cadena de texto.',
        'role.exists' => 'El rol seleccionado no es válido.'
    ];

    $usuario = User::findOrFail($id);

    // Validación de los datos
    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255', 'regex:/^(?!.*\s{2,})[A-Za-zÁÉÍÓÚáéíóúÑñ]+(?:\s[A-Za-zÁÉÍÓÚáéíóúÑñ]+)*$/u'],
        'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'unique:users,email,'.$usuario->id],
        'password' => ['nullable', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{8,}$/'],
        'role' => ['required', 'string', 'exists:roles,name'],
    ], $mensajes);

    if ($validator->fails()) {
        session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al actualizar la informacion del usuario');
            session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
            
            return redirect()->route('usuarios.edit',$id)
                             ->withErrors($validator)
                             ->withInput();
    }

    try {
        // Actualizar los datos del usuario
        $usuario->name = $request->name;
        $usuario->email = $request->email;

        // Solo actualizar la contraseña si el usuario ingresó una nueva
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        // Actualizar el rol del usuario
        $usuario->syncRoles([$request->role]);

        // Script de éxito con SweetAlert
        session()->flash('alert_type', 'success');
        session()->flash('alert_message', '¡Se ha actualizado la informacion de del usuario correctamente!');
        return redirect()->route('Ver_usuarios');

    } catch (\Exception $e) {

        session()->flash('alert_type', 'error');
        session()->flash('alert_message', 'Hubo un error al actualizar la informacion deñ usuario.');
        return redirect()->route('Ver_usuarios');
    }
}

}
