<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\fotos;
use App\Models\galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GaleriaController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $galerias = galeria::with('fotos')->orderBy('created_at', 'desc')->paginate(3);
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')
                         ->take(3)
                         ->get();

        return view("paginas_publicas.galeria_publica", compact('galerias','noticias'));
        /* return response()->json([
            'success' => true,
            'data' => $galeria,
            'message' => 'Galerias encontradas exitosamente',
        ], 201); */
    }

    public function index_logeado()
    {
        $galerias = galeria::with('fotos')->orderBy('created_at', 'desc')->paginate(24);

        return view("galerias.index", compact('galerias'));
    }

    public function create()
    {
        return view("galerias.create");
    }

    public function store(Request $request)
    {
        $mensajes = [
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.max' => 'El titulo de la galeria no debe sobrepasar los 255 caracteres.',
            'titulo.regex' => 'El título debe contener al menos una letra y solo puede incluir letras, números, espacios y los siguientes símbolos permitidos: . , & - ( ) : ; \' "',

            'imagen.required' => 'Debe adjuntar al menos un imagen.',

            'imagen.*.file' => 'Cada imagen debe ser un imagen válido.',
            'imagen.*.mimes' => 'Solo se permiten imagen en formato: jpeg, png, jpg o pdf.',
        ];

            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255|regex:/^(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ])[\wÁÉÍÓÚáéíóúÑñ\s.,&\-():;\'"]+$/u',
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
                return redirect()->route('crear_Galeria') // Cambia por la ruta de tu formulario
                    ->withErrors($validator) // Enviar errores a la vista
                    ->withInput();
            }

        try {

            $galeria = new galeria();

            $galeria->id_usu = Auth::id();
            $galeria->titulo = $request->titulo;

            $galeria->save();

            $archivosGuardados = [];

            if ($request->hasFile('imagen')) {
                foreach ($request->file('imagen') as $imagen) {
                    $extension = $imagen->getClientOriginalExtension();
                    $nombreArchivo = 'imagen_' . uniqid() . '.' . $extension;
                    $ruta = public_path('img/galeria/');
                    $imagen->move($ruta, $nombreArchivo);

                    $documentacion = new fotos();
                    $documentacion->id_galeria = $galeria->id;
                    $documentacion->imagen = $nombreArchivo;
                    $documentacion->save();

                    $archivosGuardados[] = $documentacion;
                }
            }

            $script = "<script>
            Swal.fire({
                title: '¡Éxito!',
                text: '¡Se ha creado la galeria correctamente!',
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
        return redirect()->route('galerias.auth')->with('script', $script);

        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al crear la Galeria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $galeria = galeria::find($id);
        $documentos_galeria = fotos::where('id_galeria', $id)->get();

        return view("galerias.edit", compact('galeria','documentos_galeria'));
    }

    public function update(Request $request, $id)
    {
        $mensajes = [
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.max' => 'El titulo de la galeria no debe sobrepasar los 255 caracetres.',
            'titulo.regex' => 'El título debe contener al menos una letra y solo puede incluir letras, números, espacios y los siguientes símbolos permitidos: . , & - ( ) : ; \' "',

            'imagen.required' => 'Debe adjuntar al menos un imagen.',

            'imagen.*.file' => 'Cada imagen debe ser un imagen válido.',
            'imagen.*.mimes' => 'Solo se permiten imagen en formato: jpeg, png, jpg o pdf.',
        ];

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255|regex:/^(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ])[\wÁÉÍÓÚáéíóúÑñ\s.,&\-():;\'"]+$/u',
        ], $mensajes);

        if ($validator->fails()) {
            return redirect()->route('editar_Galeria', ['id' => $id]) // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

        try {
            $galeria = galeria::find($id);

            if (!$galeria) {
                return response()->json(['message' => 'Galeria no encontrada'], 404);
            }

            $galeria->titulo = $request->titulo;

            $galeria->save();

            $script = "<script>
            Swal.fire({
                title: '¡Éxito!',
                text: '¡Se ha actualizado el titulo de la galeria correctamente!',
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
        return redirect()->route('galerias.auth')->with('script', $script);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la galeria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $galeria = galeria::find($id);

            if (!$galeria) {
                return response()->json(['message' => 'Galeria no encontrada'], 404);
            }

            $galeria->delete();

            $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha eliminado correctamente la galeria!',
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
            return redirect()->route('galerias.auth')->with('script', $script);
        } catch (\Exception $e) {
            // Manejo de errores
            $script = "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al eliminar la galeria',
                icon: 'error',
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                backdrop: false,
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
        return redirect()->route('galerias.auth')->with('script', $script);
        }
        
    }

}
