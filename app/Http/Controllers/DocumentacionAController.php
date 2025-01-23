<?php

namespace App\Http\Controllers;

use App\Models\documentacion_a;
use Illuminate\Http\Request;

class DocumentacionAController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $documentacion_a = documentacion_a::all();
        return response()->json([
            'success' => true,
            'data' => $documentacion_a,
            'message' => 'Documentaciones de Actividad encontradas exitosamente',
        ], 201);
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_actividades' => 'required|int',
                'archivo' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf,doc,docx'],
            ]);

        try {
            $documentacion_a = new documentacion_a();

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = public_path('documentacion_a/');
                $archivo->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $documentacion_a->id_actividades = $request->id_actividades;
            $documentacion_a->archivo = $archivo_n;


            $documentacion_a->save();

            return response()->json([
                'success' => true,
                'data' => $documentacion_a,
                'message' => 'Documento de Actividad agregado exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al agregar el documento de actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'archivo' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf,doc,docx'],
        ]);

        try {
            $documentacion_a = documentacion_a::find($id);

            if (!$documentacion_a) {
                return response()->json(['message' => 'Documentacion de actividad no encontrada'], 404);
            }

            // Eliminar el archivo previo si existe
            $rutaArchivoPrevio = public_path('documentacion_a/' . $documentacion_a->archivo);
            if (file_exists($rutaArchivoPrevio)) {
                unlink($rutaArchivoPrevio);
            }

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = public_path('documentacion_a/');
                $archivo->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $documentacion_a->archivo = $archivo_n;

            $documentacion_a->save();

            return response()->json([
                'success' => true,
                'data' => $documentacion_a,
                'message' => 'Documentacion de actividad actualizada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la documentacion de actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $documentacion_a = documentacion_a::find($id);

            if (!$documentacion_a) {
                return response()->json(['message' => 'Documentacion de actividad no encontrada'], 404);
            }

            $documentacion_a->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Documentacion de Actividad eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la documentacion de actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }
}
