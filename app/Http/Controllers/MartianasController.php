<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\martianas;
use Illuminate\Http\Request;

class MartianasController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $martianas = martianas::all();
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();
        /* return response()->json([
            'success' => true,
            'data' => $martianas,
            'message' => 'Actividades martianas encontradas exitosamente',
        ], 201); */
        return view("paginas_publicas.actividades_martianas", compact('martianas','noticias'));
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_usu' => 'required|int',
                'titulo' => 'required|string|max:255',
                'cuerpo' => 'string',
            ]);

        try {
            $martiana = new martianas();

            $martiana->id_usu = $request->id_usu;
            $martiana->titulo = $request->titulo;
            $martiana->cuerpo = $request->cuerpo;

            $martiana->save();

            return response()->json([
                'success' => true,
                'data' => $martiana,
                'message' => 'Actividad martiana creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al crear la actividad martiana',
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
            $martiana = martianas::find($id);

            if (!$martiana) {
                return response()->json(['message' => 'Actividad martiana no encontrada'], 404);
            }

            $martiana->titulo = $request->titulo;
            $martiana->cuerpo = $request->cuerpo;

            $martiana->save();

            return response()->json([
                'success' => true,
                'data' => $martiana,
                'message' => 'Actividad martiana actualizada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la actividad martiana',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $martiana = martianas::find($id);

            if (!$martiana) {
                return response()->json(['message' => 'Actividad martiana no encontrada'], 404);
            }

            $martiana->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Actividad martiana eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la actividad martiana',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }
}
