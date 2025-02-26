<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\convocatoria;
use App\Models\documentacion_convocatorias;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConvocatoriaController extends Controller
{
    //
    public function index()
    {
        $convocatorias = convocatoria::orderBy('fecha', 'desc')->paginate(24);
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();

        // Procesar el cuerpo de las noticias
        foreach ($convocatorias as $convocatoria) {
            $convocatoria->cuerpo_truncado = $this->truncateHtml($convocatoria->cuerpo, 100);
        }

        /* return response()->json([
            'success' => true,
            'data' => $convocatorias,
            'message' => 'Convocatorias encontradas exitosamente',
        ], 201); */
        return view("paginas_publicas.convocatorias_publicas", compact('convocatorias','noticias'));
    }

    function truncateHtml($html, $limit = 100) {
        $text = strip_tags($html); // Quita las etiquetas HTML
        $truncated = Str::limit($text, $limit); // Aplica el límite
        return $truncated;
    }

    public function index_logeado()
    {
        $convocatorias = convocatoria::orderBy('fecha', 'desc')->paginate(24);
        
        foreach ($convocatorias as $convocatoria) {
            $convocatoria->cuerpo_truncado = $this->truncateHtml($convocatoria->cuerpo, 100);
        }

        return view("convocatorias.index", compact('convocatorias'));
    }

    public function create()
    {
        return view("convocatorias.create");
    }

    public function store(Request $request)
    {
        $mensajes = [
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.max' => 'El titulo puede contener un maximo de 255 caracteres.',
            'titulo.regex' => 'El título debe contener al menos dos palabras, una vocal, una consonante y solo puede incluir letras, números, espacios y los siguientes signos permitidos: , . - : ; ( ) \' " ',
            'fecha.required' => 'Debes ingresar una fecha válido.',
        ];

        $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255|regex:/^(?=.*[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])(?=.*[aeiouáéíóúAEIOUÁÉÍÓÚ])[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s,.\-:;()\'"]+$/u',
                'cuerpo' => 'nullable|string',
                'fecha' => 'required|date',
                'agregar_file'  => 'required',
            ],$mensajes);

            if ($validator->fails()) {
                return redirect()->route('crear_Convocatoria') // Cambia por la ruta de tu formulario
                    ->withErrors($validator) // Enviar errores a la vista
                    ->withInput();
            }

        try {
            $convocatoria = new convocatoria();

            $convocatoria->id_usu = Auth::id();
            $convocatoria->titulo = $request->titulo;
            $convocatoria->cuerpo = $request->cuerpo;
            $convocatoria->fecha = $request->fecha;

            $convocatoria->save();

            // Obtener el ID de la actividad recién creada
            $convocatoriaId = $convocatoria->id;

            /* $actividades = actividades::orderBy('fecha', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            //return view("actividades.index", compact('actividades'));
            return redirect()->route('actividades.auth')->with('success', 'Actividad creada exitosamente'); */

            $AgregarFile = $request->agregar_file;

            if ($AgregarFile == 0) {

                $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha creado la convocatoria correctamente!',
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
            return redirect()->route('convocatorias.auth')->with('script', $script);

            }else{
                return redirect()->route('documentacion_convocatoria.crear', ['id' => $convocatoriaId]);
            }

            /* return response()->json([  documentacion_actividad.crear
                'success' => true,
                'data' => $actividad,
                'message' => 'Actividad creada exitosamente',
            ], 201); */
        } catch (\Exception $e) {
            /* return response()->json([
                'success' => false,
                'message' => 'Hubo un error al crear la actividad',
                'error' => $e->getMessage(),
            ], 500); */

            /* $actividades = actividades::orderBy('fecha', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            return redirect()->route('actividades.auth')->with('error', 'Hubo un error al crear la actividad'); */

            $script = "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al crear la convocatoria',
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

        return redirect()->route('convocatorias.auth')->with('script', $script);

        }
    }

    public function show(string $id)
    {
        $convocatoria = convocatoria::find($id);
        $documentos_convocatoria = documentacion_convocatorias::where('id_convocatoria', $id)->get();

        return view("paginas_publicas.convocatorias_publicas", compact('convocatoria','documentos_convocatoria'));
    }

    public function edit(string $id)
    {
        $convocatoria = convocatoria::find($id);

        $documento_convocatoria = documentacion_convocatorias::where('id_convocatoria', $id)->exists();
        $documentos_convocatoria = documentacion_convocatorias::where('id_convocatoria', $id)->get();

        if ($documento_convocatoria) {
            $documento = true;
        } else {
            $documento = false;
        }

        return view("convocatorias.edit", compact('convocatoria','documento','documentos_convocatoria'));
    }

    public function update(Request $request, $id)
    {
        $mensajes = [
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.max' => 'El titulo puede contener un maximo de 255 caracteres.',
            'titulo.regex' => 'El título debe contener al menos dos palabras, una vocal, una consonante y solo puede incluir letras, números, espacios y los siguientes signos permitidos: , . - : ; ( ) \' " ',
            'fecha.required' => 'Debes ingresar una fecha válido.',
        ];

        $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255|regex:/^(?=.*[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])(?=.*[aeiouáéíóúAEIOUÁÉÍÓÚ])[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s,.\-:;()\'"]+$/u',
                'cuerpo' => 'nullable|string',
                'fecha' => 'required|date',
                'agregar_file'  => 'required',
            ],$mensajes);


        if ($validator->fails()) {
            return redirect()->route('editar_Convocatoria', ['id' => $id]) // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

        try {
            $convocatoria = convocatoria::find($id);

            if (!$convocatoria) {
                return response()->json(['message' => 'Convocatoria no encontrada'], 404);
            }

            $convocatoria->titulo = $request->titulo;
            $convocatoria->cuerpo = $request->cuerpo;
            $convocatoria->fecha = $request->fecha;

            $convocatoria->save();

            /* $actividades = actividades::orderBy('fecha', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }*/

            //return view("actividades.index", compact('actividades'));
            //return redirect()->route('actividades.auth')->with('success', 'Actividad actualizada exitosamente');

            $script = "<script>
            Swal.fire({
                title: '¡Éxito!',
                text: '¡Los datos fueron actualizados correctamente!',
                icon: 'success',
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
            session()->flash('script', $script);
            return redirect()->route('convocatorias.auth');
            //->with('script', $script);

            /* return response()->json([
                'success' => true,
                'data' => $actividad,
                'message' => 'Actividad actualizada exitosamente',
            ], 201); */
        } catch (\Exception $e) {
            // Manejo de errores
           /*  return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la actividad',
                'error' => $e->getMessage(),
            ], 500); */

            /* $actividades = actividades::orderBy('fecha', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            } */

            //return redirect()->route('actividades.auth')->with('error', 'Hubo un error al actualizar la actividad');

            $script = "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al actualizar la convocatoria',
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
        session()->flash('script', $script);
        return redirect()->route('convocatorias.auth');
        //->with('script', $script);

        }
    }

    public function destroy($id)
    {
        try {
            $convocatoria = convocatoria::find($id);

            if (!$convocatoria) {
                return response()->json(['message' => 'Convocatoria no encontrado'], 404);
            }

            $convocatoria->delete();

            /* $actividades = actividades::orderBy('fecha', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            //return view("actividades.index", compact('actividades'));
            return redirect()->route('actividades.auth')->with('success', 'Actividad eliminada exitosamente'); */

            $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha eliminado correctamente la convocatoria!',
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
            return redirect()->route('convocatorias.auth')->with('script', $script);

            // Respuesta de éxito
            /* return response()->json([
                'success' => true,
                'message' => 'Actividad eliminada exitosamente',
            ], 200); */
        } catch (\Exception $e) {
            // Manejo de errores
            /* return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar la actividad',
                'error' => $e->getMessage(),
            ], 500); */

            /* $actividades = actividades::orderBy('fecha', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            return redirect()->route('actividades.auth')->with('error', 'Hubo un error al eliminar la actividad'); */

            $script = "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al eliminar la convocatoria',
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
        return redirect()->route('convocatorias.auth')->with('script', $script);
        }
        
    }

    public function search_convocatoria(Request $request)
    {
        $mensajes = [
            'keyword.required' => 'Se requiere agregar un texto.',
            'keyword.string' => 'El dato a buscar debe ser un texto.',
            'keyword.min' => 'Su busqueda debe contener minimo 3 caracteres.',
        ];

        $validator = Validator::make($request->all(), [
            'keyword' => 'required|string|min:3',
        ],$mensajes);

        if ($validator->fails()) {
            return redirect()->route('convocatorias.auth') // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

        // Obtener el término de búsqueda
        $query = $request->input('keyword');

        // Realizar la búsqueda con Scout
        $convocatorias = convocatoria::search($query)->paginate(24);

        foreach ($convocatorias as $convocatoria) {
            $convocatoria->cuerpo_truncado = $this->truncateHtml($convocatoria->cuerpo, 100);
        }

        $totalResultados = $convocatorias->total();

        // Pasar las variables necesarias a la vista
        return view("convocatorias.index", compact('convocatorias', 'totalResultados', 'query'));
    }
}
