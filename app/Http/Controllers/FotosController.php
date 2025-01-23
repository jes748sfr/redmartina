<?php

namespace App\Http\Controllers;

use App\Models\fotos;
use Illuminate\Http\Request;

class FotosController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $fotos = fotos::all();
        return response()->json([
            'success' => true,
            'data' => $fotos,
            'message' => 'fotos encontradas exitosamente',
        ], 201);
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_galeria' => 'required|int',
                'imagen' => ['required', 'file', 'mimes:jpeg,png,jpg'],
            ]);

        try {
            $foto = new fotos();

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = public_path('img/galeria/');
                $imagen->move($ruta, $nombreArchivo);
                $imagen_n = $nombreArchivo;
            }

            $foto->id_galeria = $request->id_galeria;
            $foto->imagen = $request->imagen_n;

            $foto->save();

            return response()->json([
                'success' => true,
                'data' => $foto,
                'message' => 'Foto creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al crear la foto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'imagen' => ['required', 'file', 'mimes:jpeg,png,jpg'],
        ]);

        try {
            $foto = fotos::find($id);

            if (!$foto) {
                return response()->json(['message' => 'Foto no encontrada'], 404);
            }

            // Eliminar el archivo previo si existe
            $rutaArchivoPrevio = public_path('img/galeria/' . $foto->archivo);
            if (file_exists($rutaArchivoPrevio)) {
                unlink($rutaArchivoPrevio);
            }

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = public_path('img/galeria/');
                $archivo->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $foto->imagen = $archivo_n;

            $foto->save();

            return response()->json([
                'success' => true,
                'data' => $foto,
                'message' => 'Foto actualizada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la foto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $foto = fotos::find($id);

            if (!$foto) {
                return response()->json(['message' => 'Foto no encontrada'], 404);
            }

            $foto->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Foto eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la foto',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }
}
