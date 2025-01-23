<?php

namespace App\Http\Controllers;

use App\Models\directorio;
use Illuminate\Http\Request;

class DirectorioController extends Controller
{
    //directorio
    public function index()
    {
        //$actividades = actividades::all();
        $directorio = directorio::all();
        return response()->json([
            'success' => true,
            'data' => $directorio,
            'message' => 'Directorio encontrado exitosamente',
        ], 201);
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_usu' => 'required|int',
                'area' => 'required|string|max:255',
                'imagen' => ['file', 'mimes:jpeg,png,jpg', 'max:12288'],
                'nombre' => 'required|string|max:255',
                'correo' => 'nullable|email|max:255',
                'descripcion' => 'string|max:255',
            ]);

        try {
            $directorio = new directorio();

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = public_path('img/directorio/');
                $imagen->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $directorio->id_usu = $request->id_usu;
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

            $directorio->id_usu = $request->id_usu;
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
