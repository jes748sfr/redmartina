<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\documentacion_actividades;
use Illuminate\Http\Request;

class DocumentacionActividadesController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $documentacion_actividades = documentacion_actividades::all();
        return response()->json([
            'success' => true,
            'data' => $documentacion_actividades,
            'message' => 'Documentaciones de Actividad encontradas exitosamente',
        ], 201);
    }

    public function create(string $id)
    {
        $actividad = actividades::find($id);
        return view("actividades.create_file", compact('actividad'));
    }

    public function store_img(Request $request)
    {
        $request->validate([
            'id_actividades' => 'required|int',
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
                    $ruta = public_path('documentacion_actividades/');
                    $archivo->move($ruta, $nombreArchivo);

                    $documentacion = new documentacion_actividades();
                    $documentacion->id_actividades = $request->id_actividades;
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
                    text: '¡Se ha creado la actividad correctamente!',
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
            return redirect()->route('actividades.auth')->with('script', $script);

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
            'id_actividades' => 'required|int',
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
                    $ruta = public_path('documentacion_actividades/');
                    $archivo->move($ruta, $nombreArchivo);
    
                    $documentacion = new documentacion_actividades();
                    $documentacion->id_actividades = $request->id_actividades;
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
                    text: '¡Se ha creado la actividad correctamente!',
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
            return redirect()->route('documentacion_actividad.edit', ['id' => $request->id_actividades])->with('script', $script);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir los archivos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store_file(Request $request)
    {
            $request->validate([
                'id_actividades' => 'required|int',
                'archivo' => ['required', 'file', 'mimes:pdf','max:5000'],
            ]);

        try {
            $documentacion_actividades = new documentacion_actividades();

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                //Mantener el nombre original
                //$nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $nombreArchivo = 'archivo_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = public_path('documentacion_actividades/');
                $archivo->move($ruta, $nombreArchivo);
                $archivo_n = $nombreArchivo;
            }

            $documentacion_actividades->id_actividades = $request->id_actividades;
            $documentacion_actividades->archivo = $archivo_n;


            $documentacion_actividades->save();

            return response()->json([
                'success' => true,
                'data' => $documentacion_actividades,
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

    public function edit(string $id)
    {
        $actividad = actividades::find($id);
        $documentos_actividad = documentacion_actividades::where('id_actividades', $id)->get();

        return view("actividades.edit_file", compact('actividad','documentos_actividad'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'archivo' => 'required|file|mimes:jpeg,png,jpg,pdf|max:12288', // 12MB para imágenes, 5MB para PDF
    ]);

    try {
        $documento = documentacion_actividades::find($id);

        if (!$documento) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }

        // Eliminar archivo anterior si existe
        $rutaAnterior = public_path('documentacion_actividades/' . $documento->archivo);
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }

        // Guardar nuevo archivo
        $archivo = $request->file('archivo');
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = 'archivo_' . uniqid() . '.' . $extension;
        $archivo->move(public_path('documentacion_actividades/'), $nombreArchivo);

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
