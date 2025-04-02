<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\documentacion_actividades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ActividadesController extends Controller
{
    //
    public function index()
    {

        $actividades = actividades::orderBy('fecha', 'desc')->paginate(5);

        $noticias = actividades::where('noticia', true)
                    ->orderBy('fecha', 'desc')
                    ->take(3)
                    ->with(['documentacionAs' => function ($query) {
                        $query->select('id_actividades', 'archivo')
                              ->whereRaw("archivo NOT LIKE '%.pdf'"); // Excluir PDFs
                    }])
                    ->get();

        // Procesar el cuerpo de las noticias
        foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 500);
        }
                  
        return view("paginas_publicas.actividades_red", compact('actividades','noticias'));
    }

    public function inicio()
    {
        $noticias = actividades::where('noticia', true)
                    ->orderBy('fecha', 'desc')
                    ->take(4)
                    ->with(['documentacionAs' => function ($query) {
                        $query->select('id_actividades', 'archivo'); // Asegúrate de que estos campos existen en la tabla
                    }])
                    ->get();

        foreach ($noticias as $noticia) {
            $noticia->cuerpo_truncado = $this->truncateHtml($noticia->cuerpo, 100);
        }


                         
        return view("index", compact('noticias'));
    }

    function truncateHtml($html, $limit = 100) {
        $text = strip_tags($html); // Quita las etiquetas HTML
        $truncated = Str::limit($text, $limit); // Aplica el límite
        return $truncated;
    }

    public function index_logeado()
    {
        $actividades = actividades::orderBy('fecha', 'desc')->paginate(24);
        
        foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
        }

        return view("actividades.index", compact('actividades'));
    }

    public function create()
    {
        return view("actividades.create");
    }

    public function store(Request $request)
    {
        $mensajes = [
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.max' => 'El titulo puede contener un maximo de 255 caracteres.',
            'titulo.regex' => 'El título debe contener al menos dos palabras, una vocal, una consonante y solo puede incluir letras, números, espacios y los siguientes signos permitidos: , . - : ; ( ) \' " ',
            'noticia.required' => 'Especifique si la actividad es una noticia.',
            'fecha.required' => 'Debes ingresar una fecha válido.',
        ];

            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255|regex:/^(?=.*[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])(?=.*[aeiouáéíóúAEIOUÁÉÍÓÚ])[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s,.\-:;()\'"]+$/u',
                'cuerpo' => 'nullable|string',
                'noticia' => 'required|boolean',
                'fecha' => 'required|date',
                'agregar_file'  => 'required',
            ],$mensajes);

        if ($validator->fails()) {
            return redirect()->route('crear_Actividad') // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

        try {
            $actividad = new actividades();

            $actividad->id_usu = Auth::id();
            $actividad->titulo = trim($request->titulo);
            $actividad->cuerpo = $request->cuerpo;
            $actividad->noticia = $request->noticia;
            $actividad->fecha = $request->fecha;

            $actividad->save();

            // Obtener el ID de la actividad recién creada
            $actividadId = $actividad->id;

            $AgregarFile = $request->agregar_file;

            if ($AgregarFile == 0) {

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

            }else{
                return redirect()->route('documentacion_actividad.crear', ['id' => $actividadId]);
            }

        } catch (\Exception $e) {

            $script = "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al crear la actividad',
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

        return redirect()->route('actividades.auth')->with('script', $script);

        }
    }

    public function show(string $id)
    {
        $actividad = actividades::find($id);
        $documentos_actividad = documentacion_actividades::where('id_actividades', $id)->get();

        return view("paginas_publicas.actividades_red", compact('actividad','documentos_actividad'));
    }

    public function edit(string $id)
    {
        $actividad = actividades::findOrFail($id);

        $documento_actividad = documentacion_actividades::where('id_actividades', $id)->exists();
        $documentos_actividad = documentacion_actividades::where('id_actividades', $id)->get();

        if ($documento_actividad) {
            $documento = true;
        } else {
            $documento = false;
        }

        return view("actividades.edit", compact('actividad','documento','documentos_actividad'));
    }

    public function update(Request $request, $id)
    {
        $mensajes = [
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.max' => 'El titulo puede contener un maximo de 255 caracteres.',
            'titulo.regex' => 'El título debe contener al menos dos palabras, una vocal, una consonante y solo puede incluir letras, números, espacios y los siguientes signos permitidos: , . - : ; ( ) \' " ',
            'noticia.required' => 'Especifique si la actividad es una noticia.',
            'fecha.required' => 'Debes ingresar una fecha válido.',
        ];

            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255|regex:/^(?=.*[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])(?=.*[aeiouáéíóúAEIOUÁÉÍÓÚ])[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s,.\-:;()\'"]+$/u',
                'cuerpo' => 'nullable|string',
                'noticia' => 'required|boolean',
                'fecha' => 'required|date',
            ],$mensajes);

        if ($validator->fails()) {
            return redirect()->route('editar_Actividad', ['id' => $id]) // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

        try {
            $actividad = actividades::find($id);

            if (!$actividad) {
                return response()->json(['message' => 'Actividad no encontrada'], 404);
            }

            $actividad->titulo = $request->titulo;
            $actividad->cuerpo = $request->cuerpo;
            $actividad->noticia = $request->noticia;
            $actividad->fecha = $request->fecha;

            $actividad->save();

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
            return redirect()->route('actividades.auth');

        } catch (\Exception $e) {

            $script = "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al actualizar la actividad',
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
        return redirect()->route('actividades.auth');
        //->with('script', $script);

        }
    }

    public function destroy($id)
    {
        try {
            $actividad = actividades::find($id);

            if (!$actividad) {
                return response()->json(['message' => 'Actividad no encontrado'], 404);
            }

            $actividad->delete();

            $script = "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Se ha eliminado correctamente la actividad!',
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

            $script = "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al eliminar la actividad',
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
        return redirect()->route('actividades.auth')->with('script', $script);
        }
        
    }

    public function search_actividad(Request $request)
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
            return redirect()->route('actividades.auth') // Cambia por la ruta de tu formulario
                ->withErrors($validator) // Enviar errores a la vista
                ->withInput();
        }

        // Obtener el término de búsqueda
        $query = $request->input('keyword');

        // Realizar la búsqueda con Scout
        $actividades = actividades::search($query)->paginate(24);

        foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
        }

        $totalResultados = $actividades->total();

        // Pasar las variables necesarias a la vista
        return view("actividades.index", compact('actividades', 'totalResultados', 'query'));
    }
}
