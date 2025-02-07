<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\documentacion_actividades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class ActividadesController extends Controller
{
    //
    public function index()
    {
        $actividades = actividades::orderBy('fecha', 'desc')->get();
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
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
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
        $actividades = actividades::orderBy('fecha', 'desc')->get();
        
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
            $request->validate([
                'titulo' => 'required|string|max:255',
                'cuerpo' => 'string',
                'noticia' => 'required|boolean',
                'fecha' => 'required|date',
                'agregar_file'  => 'required',
            ]);

        try {
            $actividad = new actividades();

            $actividad->id_usu = Auth::id();
            $actividad->titulo = $request->titulo;
            $actividad->cuerpo = $request->cuerpo;
            $actividad->noticia = $request->noticia;
            $actividad->fecha = $request->fecha;

            $actividad->save();

            // Obtener el ID de la actividad recién creada
            $actividadId = $actividad->id;

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
        $actividad = actividades::find($id);

        $documento_actividad = documentacion_actividades::where('id_actividades', $id)->exists();

        if ($documento_actividad) {
            $documento = true;
        } else {
            $documento = false;
        }

        return view("actividades.edit", compact('actividad','documento'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'cuerpo' => 'string',
            'noticia' => 'required|boolean',
            'fecha' => 'required',
        ]);

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
            return redirect()->route('actividades.auth');
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

            /* $actividades = actividades::orderBy('fecha', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            //return view("actividades.index", compact('actividades'));
            return redirect()->route('actividades.auth')->with('success', 'Actividad eliminada exitosamente'); */

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
        // Validar el término de búsqueda
        $request->validate([
            'keyword' => 'required|string|min:1',
        ]);

        // Obtener el término de búsqueda
        $query = $request->input('keyword');

        // Realizar la búsqueda con Scout
        $actividades = actividades::search($query)->get();

        foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
        }

        $totalResultados = $actividades->count();

        // Pasar las variables necesarias a la vista
        return view("actividades.index", compact('actividades', 'totalResultados', 'query'));
    }
}
