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
        //$actividades = actividades::all();
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
        
                    // Bloquear específicamente JFIF
                    /* if ($extension === 'jfif' || $mimeType === 'image/jpeg' && $file->getClientOriginalName() !== preg_replace('/\.[^.]+$/', '', $file->getClientOriginalName()) . '.jpg') {
                        return $fail("El formato JFIF no está permitido.");
                    } */
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
            return redirect()->route('documentacion_martiana.crear', ['id' => $request->id_martianas]) // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
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

            /* return response()->json([
                'success' => true,
                'data' => $archivosGuardados,
                'message' => 'Archivos subidos correctamente',
            ], 201); */

            $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha creado la actividad martiana correctamente!',
                    icon: 'success',
                    position: 'top-end', // Coloca la alerta en la esquina superior derecha
                    showConfirmButton: false, // Oculta el botón de 'OK'
                    timer: 1000, // Desaparece en 1 segundo
                    timerProgressBar: true,
                    backdrop: false, // No oscurece la pantalla
                    allowOutsideClick: true,
                    customClass: {
                        popup: 'swal-popup', 
                        title: 'swal-title', 
                        text: 'swal-text',
                    },
                }).then(() => {
                history.replaceState({}, document.title, window.location.pathname); // Limpiar el mensaje de la URL
                setTimeout(() => {
                    // Borrar el mensaje flash después de la alerta
                    window.location.reload(); // Recargar la página para que se borre la sesión correctamente
                }, 1200); // 1.2 segundos después de mostrar el mensaje
            });
        </script>";

            // Pasar el script a la vista
            return redirect()->route('martianas.auth')->with('script', $script);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir los archivos',
                'error' => $e->getMessage(),
            ], 500);
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
        
                    // Bloquear específicamente JFIF
                    /* if ($extension === 'jfif' || $mimeType === 'image/jpeg' && $file->getClientOriginalName() !== preg_replace('/\.[^.]+$/', '', $file->getClientOriginalName()) . '.jpg') {
                        return $fail("El formato JFIF no está permitido.");
                    } */
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
            return redirect()->route('documentacion_martiana.edit', ['id' => $request->id_martianas]) // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
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
    
            /* return response()->json([
                'success' => true,
                'data' => $archivosGuardados,
                'message' => 'Archivos subidos correctamente',
            ], 201); */

            $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha creado la actividad martiana correctamente!',
                    icon: 'success',
                    position: 'top-end', // Coloca la alerta en la esquina superior derecha
                    showConfirmButton: false, // Oculta el botón de 'OK'
                    timer: 1000, // Desaparece en 1 segundo
                    timerProgressBar: true,
                    backdrop: false, // No oscurece la pantalla
                    allowOutsideClick: true,
                    customClass: {
                        popup: 'swal-popup', 
                        title: 'swal-title', 
                        text: 'swal-text',
                    },
                }).then(() => {
                history.replaceState({}, document.title, window.location.pathname); // Limpiar el mensaje de la URL
                setTimeout(() => {
                    // Borrar el mensaje flash después de la alerta
                    //window.location.reload(); // Recargar la página para que se borre la sesión correctamente
                }, 1200); // 1.2 segundos después de mostrar el mensaje
            });
        </script>";

            // Pasar el script a la vista
            return redirect()->route('documentacion_martiana.edit', ['id' => $request->id_martianas])->with('script', $script);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir los archivos',
                'error' => $e->getMessage(),
            ], 500);
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

            // Bloquear específicamente JFIF
            /* if ($extension === 'jfif' || ($mimeType === 'image/jpeg' && $extension !== 'jpg' && $extension !== 'jpeg')) {
                return $fail("El formato JFIF no está permitido.");
            } */
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
            return redirect()->route('documentacion_martiana.edit', ['id' => $mar->id]) // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

    try {
        $documento = documentacion_martianas::find($id);

        if (!$documento) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }

        // Eliminar archivo anterior si existe
        //$rutaAnterior = public_path('documentacion_martianas/' . $documento->archivo);
        $rutaAnterior = Storage::disk('public')->path('documentacion_martianas/' . $documento->archivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }

        // Guardar nuevo archivo
        $archivo = $request->file('archivo');
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
        /* $archivo->move(public_path('documentacion_martianas/'), $nombreArchivo); */
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

        //$rutaAnterior = public_path('documentacion_martianas/' . $documentacion_martianas->archivo);
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
