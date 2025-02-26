<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\documentacion_martianas;
use App\Models\martianas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MartianasController extends Controller
{
    //
    public function index()
    {
        //$actividades = actividades::all();
        $martianas = martianas::orderBy('fecha', 'desc')->paginate(24);
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();

        // Procesar el cuerpo de las noticias
        foreach ($martianas as $martiana) {
            $martiana->cuerpo_truncado = $this->truncateHtml($martiana->cuerpo, 100);
        }

        return view("paginas_publicas.actividades_martianas", compact('martianas','noticias'));
    }

    function truncateHtml($html, $limit = 100) {
        $text = strip_tags($html); // Quita las etiquetas HTML
        $truncated = Str::limit($text, $limit); // Aplica el límite
        return $truncated;
    }

    public function index_logeado()
    {
        $martianas = martianas::orderBy('fecha', 'desc')->paginate(24);
        
        foreach ($martianas as $martiana) {
            $martiana->cuerpo_truncado = $this->truncateHtml($martiana->cuerpo, 100);
        }

        return view("martianas.index", compact('martianas'));
    }

    public function create()
    {
        return view("martianas.create");
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
            ], $mensajes);

            if ($validator->fails()) {
                return redirect()->route('crear_Martiana') // Cambia por la ruta de tu formulario
                    ->withErrors($validator) // Enviar errores a la vista
                    ->withInput();
            }

        try {
            $martiana = new martianas();

            $martiana->id_usu = Auth::id();
            $martiana->titulo = $request->titulo;
            $martiana->cuerpo = $request->cuerpo;
            $martiana->fecha = $request->fecha;

            $martiana->save();

            // Obtener el ID de la actividad recién creada
            $martianaId = $martiana->id;

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

            }else{
                return redirect()->route('documentacion_martiana.crear', ['id' => $martianaId]);
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
                text: 'Hubo un error al crear la actividad martiana',
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

        return redirect()->route('martianas.auth')->with('script', $script);

        }
    }

    public function show(string $id)
    {
        $martiana = martianas::find($id);
        $documentos_martiana = documentacion_martianas::where('id_martianas', $id)->get();

        return view("paginas_publicas.actividades_martianas", compact('martiana','documentos_martiana'));
    }

    public function edit(string $id)
    {
        $martiana = martianas::find($id);

        $documento_martiana = documentacion_martianas::where('id_martianas', $id)->exists();
        $documentos_martiana = documentacion_martianas::where('id_martianas', $id)->get();

        if ($documento_martiana) {
            $documento = true;
        } else {
            $documento = false;
        }

        return view("martianas.edit", compact('martiana','documento','documentos_martiana'));
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
            'fecha' => 'required',
        ], $mensajes);

        if ($validator->fails()) {
            return redirect()->route('editar_Martiana', ['id' => $id]) // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }


        try {
            $martiana = martianas::find($id);

            if (!$martiana) {
                return response()->json(['message' => 'Actividad martiana no encontrada'], 404);
            }

            $martiana->titulo = $request->titulo;
            $martiana->cuerpo = $request->cuerpo;
            $martiana->fecha = $request->fecha;

            $martiana->save();

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
            return redirect()->route('martianas.auth');
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
                text: 'Hubo un error al actualizar la actividad martiana',
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
        return redirect()->route('martianas.auth');
        //->with('script', $script);

        }
    }

    public function destroy($id)
    {
        try {
            $martiana = martianas::find($id);

            if (!$martiana) {
                return response()->json(['message' => 'Actividad martiana no encontrado'], 404);
            }

            $martiana->delete();

            /* $actividades = actividades::orderBy('fecha', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            //return view("actividades.index", compact('actividades'));
            return redirect()->route('actividades.auth')->with('success', 'Actividad eliminada exitosamente'); */

            $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha eliminado correctamente la actividad martiana!',
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
                text: 'Hubo un error al eliminar la actividad martiana',
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
        return redirect()->route('martianas.auth')->with('script', $script);
        }
        
    }

    public function search_martiana(Request $request)
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
            return redirect()->route('martianas.auth') // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

        // Obtener el término de búsqueda
        $query = $request->input('keyword');

        // Realizar la búsqueda con Scout
        $martianas = martianas::search($query)->paginate(24);

        foreach ($martianas as $martiana) {
            $martiana->cuerpo_truncado = $this->truncateHtml($martiana->cuerpo, 100);
        }

        $totalResultados = $martianas->total();

        // Pasar las variables necesarias a la vista
        return view("martianas.index", compact('martianas', 'totalResultados', 'query'));
    }
}
