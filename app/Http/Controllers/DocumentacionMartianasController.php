<?php

namespace App\Http\Controllers;

use App\Models\documentacion_martianas;
use App\Models\martianas;
use Illuminate\Http\Request;

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
        $martiana = martianas::find($id);
        return view("martianas.create_file", compact('martiana'));
    }

    public function store_img(Request $request)
    {
        $request->validate([
            'id_martianas' => 'required|int',
            'archivo' => ['required'],
            'archivo.*' => [
                'file',
                function ($attribute, $file, $fail) {
                    $maxSize = ($file->getClientOriginalExtension() === 'pdf') ? 5120 : 12288; // 5MB para PDF, 12MB para imágenes
                    if ($file->getSize() > $maxSize * 1024) {
                        return $fail("El archivo {$file->getClientOriginalName()} excede el tamaño permitido.");
                    }
                },
                'mimes:jpeg,png,jpg,pdf'
            ],
        ]);

        try {
            $archivosGuardados = [];

            if ($request->hasFile('archivo')) {
                foreach ($request->file('archivo') as $archivo) {
                    $extension = $archivo->getClientOriginalExtension();
                    $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
                    $ruta = public_path('documentacion_martianas/');
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
        $request->validate([
            'id_martianas' => 'required|int',
            'archivo' => ['required'],
            'archivo.*' => [
                'file',
                function ($attribute, $file, $fail) {
                    $maxSize = ($file->getClientOriginalExtension() === 'pdf') ? 5120 : 12288; // 5MB para PDF, 12MB para imágenes
                    if ($file->getSize() > $maxSize * 1024) {
                        return $fail("El archivo {$file->getClientOriginalName()} excede el tamaño permitido.");
                    }
                },
                'mimes:jpeg,png,jpg,pdf'
            ],
        ]);
    
        try {
            $archivosGuardados = [];
    
            if ($request->hasFile('archivo')) {
                foreach ($request->file('archivo') as $archivo) {
                    $extension = $archivo->getClientOriginalExtension();
                    $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
                    $ruta = public_path('documentacion_martianas/');
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
        $martiana = martianas::find($id);
        $documentos_martiana = documentacion_martianas::where('id_martianas', $id)->get();

        return view("martianas.edit_file", compact('martiana','documentos_martiana'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'archivo' => 'required|file|mimes:jpeg,png,jpg,pdf|max:12288', // 12MB para imágenes, 5MB para PDF
    ]);

    try {
        $documento = documentacion_martianas::find($id);

        if (!$documento) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }

        // Eliminar archivo anterior si existe
        $rutaAnterior = public_path('documentacion_martianas/' . $documento->archivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }

        // Guardar nuevo archivo
        $archivo = $request->file('archivo');
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
        $archivo->move(public_path('documentacion_martianas/'), $nombreArchivo);

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
