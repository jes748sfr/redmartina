<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\directorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class DirectorioController extends Controller
{
    //directorio
    public function index()
{
    // Agrupar solo el nivel 3 por país
    $directorios = directorio::where('nivel', 3)
        ->orderBy('pais')
        ->get()
        ->groupBy('pais');

    // Obtener los usuarios destacados sin agrupación por país
    $usuariosDestacados = directorio::whereIn('nivel', [1, 2])
        ->orderBy('nivel') // Ordenar por nivel para priorizar el nivel 1 sobre el 2
        ->get();

    $noticias = actividades::where('noticia', true)
        ->orderBy('fecha', 'desc')
        ->take(3)
        ->with(['documentacionAs' => function ($query) {
            $query->select('id_actividades', 'archivo')
                ->whereRaw("archivo NOT LIKE '%.pdf'"); // Excluir PDFs
        }])
        ->get();

    return view("paginas_publicas.directorio_publico", compact('directorios', 'noticias', 'usuariosDestacados'));
}


    public function index_logeado()
    {
        $directorios = directorio::orderBy('created_at', 'desc')->paginate(24);

        return view("directorios.index", compact('directorios'));
    }

    public function create()
    {
        $ruta = public_path('img/assets/paises.csv');
        $paises = [];
    
        if (($handle = fopen($ruta, 'r')) !== FALSE) {
            fgetcsv($handle); // Omitir la primera fila (cabecera)
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $paises[] = ['sigla' => $data[0], 'nombre' => $data[1]];
            }
            fclose($handle);
        }

        return view("directorios.create", compact('paises'));
    }

    public function store(Request $request)
{

    $mensajes = [
        'area.required' => 'El área es obligatoria.',
        'area.regex' => 'El área solo puede contener letras, espacios y los siguientes símbolos permitidos: & . , - / ()',
        'nivel.required' => 'Se requiere el nivel en el directorio.',
        'nivel.integer' => 'El nivel debe ser numerico.',
        'pais.required' => 'El pais es obligatorio.',
        'nombre.required' => 'El nombre no puede estar vacío.',
        'nombre.regex' => 'El nombre debe contener solo letras, espacios, apóstrofes y guiones, con al menos 3 caracteres.',
        'correo.email' => 'Debes ingresar un correo válido.',
        'correo.unique' => 'Este correo ya está registrado.',
        'imagen.mimes' => 'La imagen debe ser en formato JPEG, JPG o PNG.',
        'imagen.max' => 'La imagen no puede ser mayor a 12MB.',
    ];

    $validator = Validator::make($request->all(), [
        'area' => 'required|string|max:255|regex:/^(?!.*\d)(?!.*[^A-Za-zÁÉÍÓÚáéíóúÑñ\s&.,\-\/()]).*$/u',
        'nivel' => 'required|integer',
        'pais' => 'required|string|max:255',
        'imagen' => ['file', 'mimes:jpeg,png,jpg', 'max:12288'],
        'nombre' => 'required|string|max:255|regex:/^(?=.{3,})(?!.*\d)(?!.*[^A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-.]).*$/u',
        'correo' => 'nullable|email|unique:directorios,correo|max:255',
        'descripcion' => 'nullable|string|max:255',
    ], $mensajes);

    if ($validator->fails()) {
        return redirect()->route('crear_Directorio') // Cambia por la ruta de tu formulario
            ->withErrors($validator) // Enviar errores a la vista
            ->withInput();
    }

    // Verificar si el correo ya está registrado antes del try
    if (!empty($request->correo) && directorio::where('correo', $request->correo)->exists()) {
        return response()->json([
            'success' => false,
            'errors' => ['correo' => ['El correo ingresado ya está registrado en el directorio.']],
        ], 422);
    }

    try {
        $directorio = new directorio();
        $archivo_n = null;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = 'imagen_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $ruta = public_path('img/directorio/');
            $imagen->move($ruta, $nombreArchivo);
            $archivo_n = $nombreArchivo;
        }

        $directorio->id_usu = Auth::id();
        $directorio->area = $request->area;
        $directorio->nivel = $request->nivel;
        $directorio->pais = $request->pais;
        $directorio->imagen = $archivo_n;
        $directorio->nombre = $request->nombre;
        $directorio->correo = $request->correo;
        $directorio->descripcion = $request->descripcion;
        $directorio->save();

        /* return response()->json([
            'success' => true,
            'data' => $directorio,
            'message' => 'Directorio creado exitosamente',
        ], 201); */
        $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha creado un nuevo apartado en el directorio!',
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

            // Pasar el script a la vista
            return redirect()->route('directorios.auth')->with('script', $script);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Hubo un error al crear el directorio',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function edit(string $id)
    {
        $directorio = directorio::find($id);

        $ruta = public_path('img/assets/paises.csv');
        $paises = [];
    
        if (($handle = fopen($ruta, 'r')) !== FALSE) {
            fgetcsv($handle); // Omitir la primera fila (cabecera)
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $paises[] = ['sigla' => $data[0], 'nombre' => $data[1]];
            }
            fclose($handle);
        }

        return view("directorios.edit", compact('directorio','paises'));
    }

    public function update(Request $request, $id)
    {
        $mensajes = [
            'area.required' => 'El área es obligatoria.',
            'area.regex' => 'El área solo puede contener letras, espacios y los siguientes símbolos permitidos: & . , - / ()',
            'nivel.required' => 'Se requiere el nivel en el directorio.',
            'nivel.integer' => 'El nivel debe ser numerico.',
            'pais.required' => 'El pais es obligatorio.',
            'nombre.required' => 'El nombre no puede estar vacío.',
            'nombre.regex' => 'El nombre debe contener solo letras, espacios, apóstrofes y guiones, con al menos 3 caracteres.',
            'correo.email' => 'Debes ingresar un correo válido.',
            'correo.unique' => 'Este correo ya está registrado.',
            'imagen.mimes' => 'La imagen debe ser en formato JPEG, JPG o PNG.',
            'imagen.max' => 'La imagen no puede ser mayor a 12MB.',
        ];

        $validator = Validator::make($request->all(), [
            'area' => 'required|string|max:255|regex:/^(?!.*\d)(?!.*[^A-Za-zÁÉÍÓÚáéíóúÑñ\s&.,\-\/()]).*$/u',
            'nivel' => 'required|integer',
            'pais' => 'required|string|max:255',
            'imagen' => ['nullable','image', 'mimes:jpeg,png,jpg', 'max:12288'],
            'nombre' => 'required|string|max:255|regex:/^(?=.{3,})(?!.*\d)(?!.*[^A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-.]).*$/u',
            'correo' => 'nullable|email|max:255|unique:directorios,correo,' . $id,
            'descripcion' => 'nullable|string|max:255',
        ], $mensajes);

        if ($validator->fails()) {
            return redirect()->route('editar_Directorio', ['id' => $id]) // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

        try {
            $directorio = directorio::find($id);
            $archivo_n = $directorio->imagen;

            if (!$directorio) {
                return response()->json(['message' => 'Directorio no encontrado'], 404);
            }

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = public_path('img/directorio/');
                $imagen->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $directorio->area = $request->area;
            $directorio->nivel = $request->nivel;
            $directorio->pais = $request->pais;
            $directorio->imagen = $archivo_n;
            $directorio->nombre = $request->nombre;
            $directorio->correo = $request->correo;
            $directorio->descripcion = $request->descripcion;

            $directorio->save();

            /* return response()->json([
                'success' => true,
                'data' => $directorio,
                'message' => 'Directorio actualizado exitosamente',
            ], 201); */
            $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha actualizado apartado en el directorio!',
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

            // Pasar el script a la vista
            return redirect()->route('directorios.auth')->with('script', $script);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar el directorio',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $directorio = directorio::find($id);

            if (!$directorio) {
                return response()->json(['message' => 'Directorio no encontrado'], 404);
            }

            $directorio->delete();

            // Respuesta de éxito
            /* return response()->json([
                'success' => true,
                'message' => 'Directorio eliminado exitosamente',
            ], 200); */
            $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha creado un eliminado apartado en el directorio!',
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

            // Pasar el script a la vista
            return redirect()->route('directorios.auth')->with('script', $script);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar el directorio',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }

}
