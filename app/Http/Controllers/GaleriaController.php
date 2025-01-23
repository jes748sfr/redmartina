<?php

namespace App\Http\Controllers;

use App\Models\galeria;
use Illuminate\Http\Request;

class GaleriaController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $galeria = galeria::all();
        return response()->json([
            'success' => true,
            'data' => $galeria,
            'message' => 'Galerias encontradas exitosamente',
        ], 201);
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_usu' => 'required|int',
                'titulo' => 'required|string|max:255',
            ]);

        try {
            $galeria = new galeria();

            $galeria->id_usu = $request->id_usu;
            $galeria->titulo = $request->titulo;

            $galeria->save();

            return response()->json([
                'success' => true,
                'data' => $galeria,
                'message' => 'Galeria creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al crear la Galeria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
        ]);

        try {
            $galeria = galeria::find($id);

            if (!$galeria) {
                return response()->json(['message' => 'Galeria no encontrada'], 404);
            }

            $galeria->titulo = $request->titulo;

            $galeria->save();

            return response()->json([
                'success' => true,
                'data' => $galeria,
                'message' => 'Galeria actualizada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la galeria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $galeria = galeria::find($id);

            if (!$galeria) {
                return response()->json(['message' => 'Galeria no encontrada'], 404);
            }

            $galeria->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Galeria eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la galeria',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }

}
