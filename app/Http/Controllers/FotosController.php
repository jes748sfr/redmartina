<?php

namespace App\Http\Controllers;

use App\Models\fotos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FotosController extends Controller
{
    //
    public function index()
    {
        $fotos = fotos::all();
        return response()->json([
            'success' => true,
            'data' => $fotos,
            'message' => 'fotos encontradas exitosamente',
        ], 201);
    }

    public function store(Request $request)
    {
        $mensajes = [
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.max' => 'El titulo de la galeria no debe sobrepasar los 255 caracteres.',

            'imagen.required' => 'Debe adjuntar al menos un imagen.',

            'imagen.*.file' => 'Cada imagen debe ser un imagen válido.',
            'imagen.*.mimes' => 'Solo se permiten imagen en formato: jpeg, png, jpg o pdf.',
        ];
        
        $validator = Validator::make($request->all(), [
            'id_galeria' => 'required|int',
            'imagen' => ['required'],
            'imagen.*' => [
                'file',
                function ($attribute, $file, $fail) {
                    $maxSize = ($file->getClientOriginalExtension() === 'pdf') ? 5120 : 12288; // 5MB para PDF, 12MB para imágenes
                    if ($file->getSize() > $maxSize * 1024) {
                        return $fail("La imagen {$file->getClientOriginalName()} excede el tamaño permitido.");
                    }
                },
                'mimes:jpeg,png,jpg,pdf'
            ],
        ], $mensajes);

        if ($validator->fails()) {
            session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Hubo un error al actualizar la foto');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('editar_Galeria', $request->id_galeria)
                                 ->withErrors($validator)
                                 ->withInput();
        }
    
        try {
            $archivosGuardados = [];
    
            if ($request->hasFile('imagen')) {
                foreach ($request->file('imagen') as $imagen) {
                    $extension = $imagen->getClientOriginalExtension();
                    $nombreArchivo = 'imagen_' . uniqid() . '.' . $extension;
                    $ruta = Storage::disk('public')->path('galeria/');
                    $imagen->move($ruta, $nombreArchivo);
    
                    $documentacion = new fotos();
                    $documentacion->id_galeria = $request->id_galeria;
                    $documentacion->imagen = $nombreArchivo;
                    $documentacion->save();
    
                    $archivosGuardados[] = $documentacion;
                }
            }
    
            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha agregado la foto a la galeria correctamente!');
            return redirect()->route('editar_Galeria', $request->id_galeria);
    
        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al actualizar la foto de la galeria');
            return redirect()->route('editar_Galeria', $request->id_galeria);
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

            $rutaArchivoPrevio = Storage::disk('public')->path('galeria/' . $foto->imagen);
            if (file_exists($rutaArchivoPrevio)) {
                unlink($rutaArchivoPrevio);
            }

            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreArchivo = 'imagen_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = Storage::disk('public')->path('galeria/');
                $archivo->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }
            $foto->imagen = $archivo_n;

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

            $rutaArchivoPrevio = Storage::disk('public')->path('galeria/' . $foto->imagen);
            if (file_exists($rutaArchivoPrevio)) {
                unlink($rutaArchivoPrevio);
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
