<?php

namespace App\Http\Controllers;

use App\Models\marianas;
use Illuminate\Http\Request;

class MarianasController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $marianas = marianas::all();
        return response()->json([
            'success' => true,
            'data' => $marianas,
            'message' => 'Actividades marianas encontradas exitosamente',
        ], 201);
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_usu' => 'required|int',
                'titulo' => 'required|string|max:255',
                'cuerpo' => 'string',
            ]);

        try {
            $mariana = new marianas();

            $mariana->id_usu = $request->id_usu;
            $mariana->titulo = $request->titulo;
            $mariana->cuerpo = $request->cuerpo;

            $mariana->save();

            return response()->json([
                'success' => true,
                'data' => $mariana,
                'message' => 'Actividad mariana creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al crear la actividad mariana',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'cuerpo' => 'string',
        ]);

        try {
            $mariana = marianas::find($id);

            if (!$mariana) {
                return response()->json(['message' => 'Actividad mariana no encontrada'], 404);
            }

            $mariana->titulo = $request->titulo;
            $mariana->cuerpo = $request->cuerpo;

            $mariana->save();

            return response()->json([
                'success' => true,
                'data' => $mariana,
                'message' => 'Actividad mariana actualizada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la actividad mariana',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $mariana = marianas::find($id);

            if (!$mariana) {
                return response()->json(['message' => 'Actividad mariana no encontrada'], 404);
            }

            $mariana->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Actividad mariana eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la actividad mariana',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }
}
