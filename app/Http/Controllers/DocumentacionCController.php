<?php

namespace App\Http\Controllers;

use App\Models\documentacion_c;
use Illuminate\Http\Request;

class DocumentacionCController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $documentacion_c = documentacion_c::all();
        return response()->json([
            'success' => true,
            'data' => $documentacion_c,
            'message' => 'Documentaciones de Convocatoria encontradas exitosamente',
        ], 201);
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_convocatoria' => 'required|int',
                'archivo' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf,doc,docx'],
            ]);

        try {
            $documentacion_c = new documentacion_c();

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = public_path('documentacion_a/');
                $archivo->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $documentacion_c->id_convocatoria = $request->id_convocatoria;
            $documentacion_c->archivo = $archivo_n;


            $documentacion_c->save();

            return response()->json([
                'success' => true,
                'data' => $documentacion_c,
                'message' => 'Documento de convocatoria agregado exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al agregar el documento de convocatoria',
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
            $documentacion_c = documentacion_c::find($id);

            if (!$documentacion_c) {
                return response()->json(['message' => 'Documentación de convocatoria no encontrada'], 404);
            }

            // Eliminar el archivo previo si existe
            $rutaArchivoPrevio = public_path('documentacion_c/' . $documentacion_c->archivo);
            if (file_exists($rutaArchivoPrevio)) {
                unlink($rutaArchivoPrevio);
            }

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = public_path('documentacion_c/');
                $archivo->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $documentacion_c->archivo = $archivo_n;

            $documentacion_c->save();

            return response()->json([
                'success' => true,
                'data' => $documentacion_c,
                'message' => 'Documentación de convocatoria actualizada exitosamente',
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
            $documentacion_c = documentacion_c::find($id);

            if (!$documentacion_c) {
                return response()->json(['message' => 'Documentación de convocatoria no encontrada'], 404);
            }

            $documentacion_c->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Documentación de convocatoria eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la documentacion de convocatoria',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }

}
