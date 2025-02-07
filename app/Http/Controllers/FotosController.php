<?php

namespace App\Http\Controllers;

use App\Models\fotos;
use Illuminate\Http\Request;

class FotosController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $fotos = fotos::all();
        return response()->json([
            'success' => true,
            'data' => $fotos,
            'message' => 'fotos encontradas exitosamente',
        ], 201);
    }

    public function store(Request $request)
    {
        $request->validate([
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
        ]);
    
        try {
            $archivosGuardados = [];
    
            if ($request->hasFile('imagen')) {
                foreach ($request->file('imagen') as $imagen) {
                    $extension = $imagen->getClientOriginalExtension();
                    $nombreArchivo = 'imagen_' . uniqid() . '.' . $extension;
                    $ruta = public_path('img/galeria/');
                    $imagen->move($ruta, $nombreArchivo);
    
                    $documentacion = new fotos();
                    $documentacion->id_galeria = $request->id_galeria;
                    $documentacion->imagen = $nombreArchivo;
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
                    text: '¡Se ha agregado la foto a la galeria correctamente!',
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
            return redirect()->route('editar_Galeria', ['id' => $request->id_galeria])->with('script', $script);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir los archivos',
                'error' => $e->getMessage(),
            ], 500);
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

            // Eliminar el archivo previo si existe
            $rutaArchivoPrevio = public_path('img/galeria/' . $foto->imagen);
            if (file_exists($rutaArchivoPrevio)) {
                unlink($rutaArchivoPrevio);
            }

            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreArchivo = 'imagen_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = public_path('img/galeria/');
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
