<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\convocatoria;
use App\Models\marianas;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{
    //
    public function index()
    {
        $actividades = actividades::all();
        return response()->json([
            'success' => true,
            'data' => $actividades,
            'message' => 'Actividades encontradas exitosamente',
        ], 201);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_usu' => 'required|int',
                'titulo' => 'required|string|max:255',
                'cuerpo' => 'string',
                'noticia' => 'required|boolean',
            ]);

        try {
            $actividad = new actividades();

            $actividad->id_usu = $request->id_usu;
            $actividad->titulo = $request->titulo;
            $actividad->cuerpo = $request->cuerpo;
            $actividad->noticia = $request->noticia;

            $actividad->save();

            return response()->json([
                'success' => true,
                'data' => $actividad,
                'message' => 'Actividad creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al crear la actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'cuerpo' => 'string',
            'noticia' => 'required|boolean',
        ]);

        try {
            $actividad = actividades::find($id);

            if (!$actividad) {
                return response()->json(['message' => 'Actividad no encontrada'], 404);
            }

            $actividad->titulo = $request->titulo;
            $actividad->cuerpo = $request->cuerpo;
            $actividad->noticia = $request->noticia;

            $actividad->save();

            return response()->json([
                'success' => true,
                'data' => $actividad,
                'message' => 'Actividad actualizada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $actividad = actividades::find($id);

            if (!$actividad) {
                return response()->json(['message' => 'Actividad no encontrado'], 404);
            }

            $actividad->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Actividad eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|min:3',
        ]);

        $keyword = $request->input('keyword');

        // Buscar coincidencias en cada modelo
        $actividades = Actividades::where('titulo', 'LIKE', "%{$keyword}%")
            ->orWhere('cuerpo', 'LIKE', "%{$keyword}%")
            ->get();

        $marianas = marianas::where('titulo', 'LIKE', "%{$keyword}%")
            ->orWhere('cuerpo', 'LIKE', "%{$keyword}%")
            ->get();

        $convocatorias = convocatoria::where('titulo', 'LIKE', "%{$keyword}%")
            ->orWhere('cuerpo', 'LIKE', "%{$keyword}%")
            ->get();

        // Combinar resultados
        $resultados = [
            'actividades' => $actividades,
            'marianas' => $marianas,
            'convocatorias' => $convocatorias,
        ];

        // Calcular el total de resultados
        $totalResultados = $actividades->count() + $marianas->count() + $convocatorias->count();

        // return response()->json([
        //     'success' => true,
        //     'data' => $resultados,
        //     'message' => 'Resultados encontrados exitosamente',
        // ], 200);

        return view("paginas_publicas.busqueda_publica", compact('resultados','keyword','totalResultados'));
    }

}
