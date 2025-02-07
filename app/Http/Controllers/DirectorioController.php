<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\directorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorioController extends Controller
{
    //directorio
    public function index()
    {
        //$actividades = actividades::all();
        $directorios = directorio::all();
        $noticias = actividades::where('noticia', true)
                    ->orderBy('fecha', 'desc')
                    ->take(3)
                    ->with(['documentacionAs' => function ($query) {
                        $query->select('id_actividades', 'archivo')
                              ->whereRaw("archivo NOT LIKE '%.pdf'"); // Excluir PDFs
                    }])
                    ->get();
       
        return view("paginas_publicas.directorio_publico", compact('directorios','noticias'));
/*         return response()->json([
            'success' => true,
            'data' => $directorio,
            'message' => 'Directorio encontrado exitosamente',
        ], 201); */
    }

    public function index_logeado()
    {
        $directorios = directorio::orderBy('created_at', 'desc')->get();

        return view("directorios.index", compact('directorios'));
    }

    public function create()
    {
        return view("directorios.create");
    }

    public function store(Request $request)
    {
            $request->validate([
                'area' => 'required|string|max:255',
                'imagen' => ['file', 'mimes:jpeg,png,jpg', 'max:12288'],
                'nombre' => 'required|string|max:255',
                'correo' => 'nullable|email|max:255',
                'descripcion' => 'string|max:255',
            ]);

        try {
            $directorio = new directorio();
            $archivo_n = null;

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'imagen_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = public_path('img/directorio/');
                $imagen->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $directorio->id_usu = Auth::id();
            $directorio->area = $request->area;
            $directorio->imagen = $archivo_n;
            $directorio->nombre = $request->nombre;
            $directorio->correo = $request->correo;
            $directorio->descripcion = $request->descripcion;

            $directorio->save();

            return response()->json([
                'success' => true,
                'data' => $directorio,
                'message' => 'directorio creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
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

        return view("directorios.edit", compact('directorio'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'area' => 'required|string|max:255',
            'imagen' => ['file', 'mimes:jpeg,png,jpg'],
            'nombre' => 'required|string|max:255',
            'correo' => 'string|max:255',
            'descripcion' => 'string|max:255',
        ]);

        try {
            $directorio = directorio::find($id);
            $archivo_n = null;

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

            $directorio->id_usu = $directorio->id_usu;
            $directorio->area = $request->area;
            $directorio->imagen = $archivo_n;
            $directorio->nombre = $request->nombre;
            $directorio->correo = $request->correo;
            $directorio->descripcion = $request->descripcion;

            $directorio->save();

            return response()->json([
                'success' => true,
                'data' => $directorio,
                'message' => 'Directorio actualizado exitosamente',
            ], 201);
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
            return response()->json([
                'success' => true,
                'message' => 'Directorio eliminado exitosamente',
            ], 200);
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
