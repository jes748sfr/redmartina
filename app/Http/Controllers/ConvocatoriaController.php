<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\convocatoria;
use Illuminate\Http\Request;

class ConvocatoriaController extends Controller
{
    //
    public function index()
    {
        $convocatorias = convocatoria::all();
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();
        /* return response()->json([
            'success' => true,
            'data' => $convocatorias,
            'message' => 'Convocatorias encontradas exitosamente',
        ], 201); */
        return view("paginas_publicas.convocatorias_publicas", compact('convocatorias','noticias'));
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_usu' => 'required|int',
                'titulo' => 'required|string|max:255',
                'cuerpo' => 'string',
            ]);

        try {
            $convocatoria = new convocatoria();

            $convocatoria->id_usu = $request->id_usu;
            $convocatoria->titulo = $request->titulo;
            $convocatoria->cuerpo = $request->cuerpo;

            $convocatoria->save();

            return response()->json([
                'success' => true,
                'data' => $convocatoria,
                'message' => 'Convocatoria creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al crear la convocatoria',
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
            $convocatoria = convocatoria::find($id);

            if (!$convocatoria) {
                return response()->json(['message' => 'Convocatoria no encontrada'], 404);
            }

            $convocatoria->titulo = $request->titulo;
            $convocatoria->cuerpo = $request->cuerpo;

            $convocatoria->save();

            return response()->json([
                'success' => true,
                'data' => $convocatoria,
                'message' => 'Convocatoria actualizada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la convocatoria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $convocatoria = convocatoria::find($id);

            if (!$convocatoria) {
                return response()->json(['message' => 'Convocatoria no encontrado'], 404);
            }

            $convocatoria->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Convocatoria eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la convocatoria',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }
}
