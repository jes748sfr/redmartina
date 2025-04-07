<?php

namespace App\Http\Controllers;

use App\Models\documentacion_martianas;
use App\Models\martianas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentacionMartianasController extends Controller
{
    //
    public function index()
    {
        $documentacion_martianas = documentacion_martianas::all();
        return response()->json([
            'success' => true,
            'data' => $documentacion_martianas,
            'message' => 'Documentaciones de actividad martiana encontrada exitosamente',
        ], 201);
    }

    public function create(string $id)
    {
        $martiana = martianas::findOrFail($id);
        return view("martianas.create_file", compact('martiana'));
    }

    public function store_img(Request $request)
    {
        $mensajes = [
            'id_actividades.required' => 'La actividad es obligatoria.',
            'id_actividades.int' => 'El ID de la actividad debe ser un número entero.',

            'archivo.required' => 'Debe adjuntar al menos un archivo.',

            'archivo.*.file' => 'Cada archivo debe ser un archivo válido.',
            /* 'archivo.*.mimes' => 'Solo se permiten archivos en formato: jpeg, png, jpg o pdf.', */
            'archivo.*.mimes' => 'El formato de imagen no es valido.',
        ];

        $validator = Validator::make($request->all(), [
            'id_martianas' => 'required|int',
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
                session()->flash('alert_message', 'Hubo un error al crear la actividad martiana');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('documentacion_martiana.crear', $request->id_martianas)
                                 ->withErrors($validator)
                                 ->withInput();
        }

        try {
            $archivosGuardados = [];

            if ($request->hasFile('archivo')) {
                foreach ($request->file('archivo') as $archivo) {
                    $extension = $archivo->getClientOriginalExtension();
                    $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
                    $ruta = Storage::disk('public')->path('documentacion_martianas/');
                    $archivo->move($ruta, $nombreArchivo);

                    $documentacion = new documentacion_martianas();
                    $documentacion->id_martianas = $request->id_martianas;
                    $documentacion->archivo = $nombreArchivo;
                    $documentacion->save();

                    $archivosGuardados[] = $documentacion;
                }
            }

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha creado la actividad martiana correctamente!');
            return redirect()->route('martianas.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al crear la actividad martiana');
            return redirect()->route('martianas.auth');
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
            'id_martianas' => 'required|int',
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
                session()->flash('alert_message', 'Hubo un error al actualizar la actividad martiana');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('documentacion_martiana.edit', $request->id_martianas)
                                 ->withErrors($validator)
                                 ->withInput();
        }
    
        try {
            $archivosGuardados = [];
    
            if ($request->hasFile('archivo')) {
                foreach ($request->file('archivo') as $archivo) {
                    $extension = $archivo->getClientOriginalExtension();
                    $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
                    $ruta = Storage::disk('public')->path('documentacion_martianas/');
                    $archivo->move($ruta, $nombreArchivo);
    
                    $documentacion = new documentacion_martianas();
                    $documentacion->id_martianas = $request->id_martianas;
                    $documentacion->archivo = $nombreArchivo;
                    $documentacion->save();
    
                    $archivosGuardados[] = $documentacion;
                }
            }

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha agregado el archivo a la actividad martiana correctamente!');
            return redirect()->route('documentacion_martiana.edit', $request->id_martianas);
    
        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al actualizar la informacion de la actividad martiana');
            return redirect()->route('documentacion_martiana.edit', $request->id_martianas);
        }
    }

    public function edit(string $id)
    {
        $martiana = martianas::findOrFail($id);
        $documentos_martiana = documentacion_martianas::where('id_martianas', $id)->get();

        return view("martianas.edit_file", compact('martiana','documentos_martiana'));
    }

    public function update(Request $request, $id)
    {
        $mensajes = [
            'archivo.required' => 'Debe adjuntar al menos un archivo.',
            'archivo.*.file' => 'Cada archivo debe ser un archivo válido.',
            //'archivo.*.mimes' => 'Solo se permiten archivos en formato: jpeg, png, jpg o pdf.',
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

            $doc = documentacion_martianas::find($id);
            $mar = martianas::find($doc->id_martianas);

            if ($validator->fails()) {
                session()->flash('alert_type', 'error');
                    session()->flash('alert_message', 'Hubo un error al actualizar la actividad martiana');
                    session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                    
                    return redirect()->route('documentacion_martiana.edit', $mar->id)
                                     ->withErrors($validator)
                                     ->withInput();
            }

        try {
            $documento = documentacion_martianas::find($id);

            if (!$documento) {
                return response()->json(['message' => 'Documento no encontrado'], 404);
            }

            $rutaAnterior = Storage::disk('public')->path('documentacion_martianas/' . $documento->archivo);
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }

            // Guardar nuevo archivo
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();
            $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
            $archivo->storeAs('documentacion_martianas', $nombreArchivo, 'public');

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
            $documentacion_martianas = documentacion_martianas::find($id);

            if (!$documentacion_martianas) {
                return response()->json(['message' => 'Documentacion de actividad martiana no encontrada'], 404);
            }

            $rutaAnterior = Storage::disk('public')->path('documentacion_martianas/' . $documentacion_martianas->archivo);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }

            $documentacion_martianas->delete();

            // Respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Documentacion de Actividad martiana eliminada exitosamente',
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
