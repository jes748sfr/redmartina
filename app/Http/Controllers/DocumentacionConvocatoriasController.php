<?php

namespace App\Http\Controllers;

use App\Models\convocatoria;
use App\Models\documentacion_convocatorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentacionConvocatoriasController extends Controller
{
    //
    public function index()
    {
        $documentacion_convocatorias = documentacion_convocatorias::all();
        return response()->json([
            'success' => true,
            'data' => $documentacion_convocatorias,
            'message' => 'Documentaciones de Convocatoria encontradas exitosamente',
        ], 201);
    }

    public function create(string $id)
    {
        $convocatoria = convocatoria::findOrFail($id);
        return view("convocatorias.create_file", compact('convocatoria'));
    }

    public function store_img(Request $request)
    {

        $mensajes = [
            'id_actividades.required' => 'La actividad es obligatoria.',
            'id_actividades.int' => 'El ID de la actividad debe ser un número entero.',

            'archivo.required' => 'Debe adjuntar al menos un archivo.',

            'archivo.*.file' => 'Cada archivo debe ser un archivo válido.',
            //'archivo.*.mimes' => 'Solo se permiten archivos en formato: jpeg, png, jpg o pdf.',
            'archivo.*.mimes' => 'El formato de imagen no es valido.',
        ];

        $validator = Validator::make($request->all(), [
            'id_convocatoria' => 'required|int',
            'archivo' => ['required'],
            'archivo.*' => [
                'file',
                function ($attribute, $file, $fail) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $mimeType = $file->getMimeType();
        
                    if (!in_array($extension, ['jpeg', 'jpg', 'png', 'pdf'])) {
                        return $fail("Solo se permiten archivos en formato: jpeg, jpg, png o pdf.");
                    }                    
        
                    $maxSize = ($extension === 'pdf') ? 5120 : 12288; // 5MB para PDF, 12MB para imágenes
                    if ($file->getSize() > $maxSize * 1024) {
                        return $fail("El archivo {$file->getClientOriginalName()} excede el tamaño permitido.");
                    }
                },
                'mimes:jpeg,png,jpg,pdf' // Solo estos formatos permitidos
            ],
        ], $mensajes);

        if ($validator->fails()) {
            session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Hubo un error al crear la convocatoria');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('documentacion_convocatoria.crear', $request->id_convocatoria)
                                 ->withErrors($validator)
                                 ->withInput();
        }

        try {
            $archivosGuardados = [];

            if ($request->hasFile('archivo')) {
                foreach ($request->file('archivo') as $archivo) {
                    $extension = $archivo->getClientOriginalExtension();
                    $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
                    $ruta = Storage::disk('public')->path('documentacion_convocatorias/');
                    $archivo->move($ruta, $nombreArchivo);

                    $documentacion = new documentacion_convocatorias();
                    $documentacion->id_convocatoria = $request->id_convocatoria;
                    $documentacion->archivo = $nombreArchivo;
                    $documentacion->save();

                    $archivosGuardados[] = $documentacion;
                }
            }

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha creado la convocatoria correctamente!');
            return redirect()->route('convocatorias.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al crear la convocatoria');
            return redirect()->route('convocatorias.auth');

        }
    }

    public function store_2(Request $request)
    {

        $mensajes = [
            'id_actividades.required' => 'La actividad es obligatoria.',
            'id_actividades.int' => 'El ID de la actividad debe ser un número entero.',

            'archivo.required' => 'Debe adjuntar al menos un archivo.',

            'archivo.*.file' => 'Cada archivo debe ser un archivo válido.',
            //'archivo.*.mimes' => 'Solo se permiten archivos en formato: jpeg, png, jpg o pdf.',
            'archivo.*.mimes' => 'El formato de imagen no es valido.',
        ];

        $validator = Validator::make($request->all(), [
            'id_convocatoria' => 'required|int',
            'archivo' => ['required'],
            'archivo.*' => [
                'file',
                function ($attribute, $file, $fail) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $mimeType = $file->getMimeType();
        
                    if (!in_array($extension, ['jpeg', 'jpg', 'png', 'pdf'])) {
                        return $fail("Solo se permiten archivos en formato: jpeg, jpg, png o pdf.");
                    }                    
        
                    $maxSize = ($extension === 'pdf') ? 5120 : 12288; // 5MB para PDF, 12MB para imágenes
                    if ($file->getSize() > $maxSize * 1024) {
                        return $fail("El archivo {$file->getClientOriginalName()} excede el tamaño permitido.");
                    }
                },
                'mimes:jpeg,png,jpg,pdf' // Solo estos formatos permitidos
            ],
        ], $mensajes);

        if ($validator->fails()) {
            session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Hubo un error al actualizar la convocatoria');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('documentacion_convocatoria.edit', $request->id_convocatoria)
                                 ->withErrors($validator)
                                 ->withInput();
        }
    
        try {
            $archivosGuardados = [];
    
            if ($request->hasFile('archivo')) {
                foreach ($request->file('archivo') as $archivo) {
                    $extension = $archivo->getClientOriginalExtension();
                    $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
                    $ruta = Storage::disk('public')->path('documentacion_convocatorias/');
                    $archivo->move($ruta, $nombreArchivo);
    
                    $documentacion = new documentacion_convocatorias();
                    $documentacion->id_convocatoria = $request->id_convocatoria;
                    $documentacion->archivo = $nombreArchivo;
                    $documentacion->save();
    
                    $archivosGuardados[] = $documentacion;
                }
            }

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha agregado el archivo a la convocatoria correctamente!');
            return redirect()->route('documentacion_convocatoria.edit', $request->id_convocatoria);
    
        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al actualizar la informacion de la convocatoria');
            return redirect()->route('documentacion_convocatoria.edit', $request->id_convocatoria);
        }
    }

    public function edit(string $id)
    {
        $convocatoria = convocatoria::findOrFail($id);
        $documentos_convocatoria = documentacion_convocatorias::where('id_convocatoria', $id)->get();

        return view("convocatorias.edit_file", compact('convocatoria','documentos_convocatoria'));
    }

    

    public function update(Request $request, $id)
    {
        $mensajes = [
            'archivo.required' => 'Debe adjuntar al menos un archivo.',
            'archivo.*.file' => 'Cada archivo debe ser un archivo válido.',
            //'archivo.*.mimes' => 'El formato de imagen no es valido.',
            'archivo.*.mimes' => 'El formato de imagen no es valido.',
        ];
    
        $validator = Validator::make($request->all(), [
           'archivo' => [
            'required',
            'file',
            function ($attribute, $file, $fail) {
                $extension = strtolower($file->getClientOriginalExtension());
                $mimeType = $file->getMimeType();
    
                if (!in_array($extension, ['jpeg', 'jpg', 'png', 'pdf'])) {
                    return $fail("Solo se permiten archivos en formato: jpeg, jpg, png o pdf.");
                }
                
    
                // Limitar tamaño (5MB para PDF, 12MB para imágenes)
                $maxSize = ($extension === 'pdf') ? 5120 : 12288; // 5MB = 5120KB, 12MB = 12288KB
                if ($file->getSize() > $maxSize * 1024) {
                    return $fail("El archivo {$file->getClientOriginalName()} excede el tamaño permitido.");
                }
            },
            'mimes:jpeg,png,jpg,pdf', // Formatos permitidos
            'max:12288', // 12MB como máximo
            ],
        ], $mensajes);

            $doc = documentacion_convocatorias::find($id);
            $con = convocatoria::find($doc->id_convocatoria);

            if ($validator->fails()) {
                session()->flash('alert_type', 'error');
                    session()->flash('alert_message', 'Hubo un error al actualizar la actividad');
                    session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                    
                    return redirect()->route('documentacion_convocatoria.edit', $con->id)
                                     ->withErrors($validator)
                                     ->withInput();
            }
    
        try {
            $documento = documentacion_convocatorias::find($id);
    
            if (!$documento) {
                return response()->json(['message' => 'Documento no encontrado'], 404);
            }
    
            // Eliminar archivo anterior si existe
            $rutaAnterior = Storage::disk('public')->path('documentacion_convocatorias/' . $documento->archivo);
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }
    
            // Guardar nuevo archivo
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();
            $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
            $archivo->storeAs('documentacion_convocatorias', $nombreArchivo, 'public');
    
            // Actualizar base de datos
            $documento->archivo = $nombreArchivo;
            $documento->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Archivo actualizado correctamente',
                'data' => $documento
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el archivo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $documentacion_convocatorias = documentacion_convocatorias::find($id);

            if (!$documentacion_convocatorias) {
                return response()->json(['message' => 'Documentacion de convocatoria no encontrada'], 404);
            }

            $rutaAnterior = Storage::disk('public')->path('documentacion_convocatorias/' . $documentacion_convocatorias->archivo);
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }

            $documentacion_convocatorias->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Documentacion de convocatoria eliminada exitosamente',
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
