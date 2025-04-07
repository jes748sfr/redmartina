<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\documentacion_actividades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentacionActividadesController extends Controller
{
    public function index()
    {
        $documentacion_actividades = documentacion_actividades::all();
        return response()->json([
            'success' => true,
            'data' => $documentacion_actividades,
            'message' => 'Documentaciones de Actividad encontradas exitosamente',
        ], 201);
    }

    public function create(string $id)
    {
        $actividad = actividades::findOrFail($id);
        return view("actividades.create_file", compact('actividad'));
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
            'id_actividades' => 'required|int',
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
                session()->flash('alert_message', 'Hubo un error al crear la actividad');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('documentacion_actividad.crear', $request->id_actividades)
                                 ->withErrors($validator)
                                 ->withInput();
        }

        try {
            $archivosGuardados = [];

            if ($request->hasFile('archivo')) {
                foreach ($request->file('archivo') as $archivo) {
                    $extension = $archivo->getClientOriginalExtension();
                    $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
                    $ruta = Storage::disk('public')->path('documentacion_actividades/');
                    $archivo->move($ruta, $nombreArchivo);

                    $documentacion = new documentacion_actividades();
                    $documentacion->id_actividades = $request->id_actividades;
                    $documentacion->archivo = $nombreArchivo;
                    $documentacion->save();

                    $archivosGuardados[] = $documentacion;
                }
            }

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha creado la actividad correctamente!');
            return redirect()->route('actividades.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al crear la actividad');
            return redirect()->route('actividades.auth');
        }
    }

    public function store_2(Request $request)
    {
        $mensajes = [
            'id_actividades.required' => 'La actividad es obligatoria.',
            'id_actividades.int' => 'El ID de la actividad debe ser un número entero.',

            'archivo.required' => 'Debe adjuntar al menos un archivo.',

            'archivo.*.file' => 'Cada archivo debe ser un archivo válido.',
            'archivo.*.mimes' => 'El formato de imagen no es valido.',
        ];

        $validator = Validator::make($request->all(), [
            'id_actividades' => 'required|int',
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
                session()->flash('alert_message', 'Hubo un error al actualizar la actividad');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('documentacion_actividad.edit', $request->id_actividades)
                                 ->withErrors($validator)
                                 ->withInput();
        }
    
        try {
            $archivosGuardados = [];
    
            if ($request->hasFile('archivo')) {
                foreach ($request->file('archivo') as $archivo) {
                    $extension = $archivo->getClientOriginalExtension();
                    $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
                    $ruta = Storage::disk('public')->path('documentacion_actividades/');
                    $archivo->move($ruta, $nombreArchivo);
    
                    $documentacion = new documentacion_actividades();
                    $documentacion->id_actividades = $request->id_actividades;
                    $documentacion->archivo = $nombreArchivo;
                    $documentacion->save();
    
                    $archivosGuardados[] = $documentacion;
                }
            }

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha agregado el archivo a la actividad correctamente!');
            return redirect()->route('documentacion_actividad.edit', $request->id_actividades);
    
        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al actualizar la informacion de la actividad');
            return redirect()->route('documentacion_actividad.edit', $request->id_actividades);
        }
    }

    public function edit(string $id)
    {
        $actividad = actividades::findOrFail($id);
        $documentos_actividad = documentacion_actividades::where('id_actividades', $id)->get();

        return view("actividades.edit_file", compact('actividad','documentos_actividad'));
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

        $doc = documentacion_actividades::find($id);
        $act = actividades::find($doc->id_actividades);

        if ($validator->fails()) {
                session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Hubo un error al actualizar la actividad');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('documentacion_actividad.edit', $act->id)
                                 ->withErrors($validator)
                                 ->withInput();
        }

    try {
        $documento = documentacion_actividades::find($id);

        if (!$documento) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }

        // Eliminar archivo anterior si existe
        $rutaAnterior = Storage::disk('public')->path('documentacion_actividades/' . $documento->archivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }

        // Guardar nuevo archivo
        $archivo = $request->file('archivo');
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
        $rutaAnterior = Storage::disk('public')->path('documentacion_actividades/' . $documento->archivo);
        $archivo->storeAs('documentacion_actividades', $nombreArchivo, 'public');


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
            $documentacion_actividades = documentacion_actividades::find($id);

            if (!$documentacion_actividades) {
                return response()->json(['message' => 'Documentacion de actividad no encontrada'], 404);
            }

            // Eliminar archivo anterior si existe
            $rutaAnterior = Storage::disk('public')->path('documentacion_actividades/' . $documentacion_actividades->archivo);
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }

            $documentacion_actividades->delete();

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
