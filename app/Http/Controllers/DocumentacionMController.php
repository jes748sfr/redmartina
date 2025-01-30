<?php

namespace App\Http\Controllers;

use App\Models\documentacion_m;
use Illuminate\Http\Request;

class DocumentacionMController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $documentacion_m = documentacion_m::all();
        return response()->json([
            'success' => true,
            'data' => $documentacion_m,
            'message' => 'Documentaciones de actividad martiana encontrada exitosamente',
        ], 201);
    }

    public function store(Request $request)
    {
            $request->validate([
                'id_martianas' => 'required|int',
                'archivo' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf,doc,docx'],
            ]);

        try {
            $documentacion_m = new documentacion_m();

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = public_path('documentacion_m/');
                $archivo->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $documentacion_m->id_martianas = $request->id_martianas;
            $documentacion_m->archivo = $archivo_n;


            $documentacion_m->save();

            return response()->json([
                'success' => true,
                'data' => $documentacion_m,
                'message' => 'Documento de actividad martiana agregada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al agregar el documento de actividad martiana',
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
            $documentacion_m = documentacion_m::find($id);

            if (!$documentacion_m) {
                return response()->json(['message' => 'Documentación de actividad martiana no encontrada'], 404);
            }

            // Eliminar el archivo previo si existe
            $rutaArchivoPrevio = public_path('documentacion_m/' . $documentacion_m->archivo);
            if (file_exists($rutaArchivoPrevio)) {
                unlink($rutaArchivoPrevio);
            }

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = public_path('documentacion_m/');
                $archivo->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $documentacion_m->archivo = $archivo_n;

            $documentacion_m->save();

            return response()->json([
                'success' => true,
                'data' => $documentacion_m,
                'message' => 'Documentación de actividad martiana actualizada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la documentacion de actividad martiana',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $documentacion_m = documentacion_m::find($id);

            if (!$documentacion_m) {
                return response()->json(['message' => 'Documentación de actividad martiana no encontrada'], 404);
            }

            $documentacion_m->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Documentación de actividad martiana eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la documentacion de actividad martiana',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }

}
