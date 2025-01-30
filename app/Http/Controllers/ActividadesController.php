<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class ActividadesController extends Controller
{
    //
    public function index()
    {
        $actividades = actividades::orderBy('created_at', 'desc')->get();
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')
                         ->take(3)
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
                         ->orderBy('created_at', 'desc')
                         ->take(3)
                         ->get();


                         
        return view("index", compact('noticias'));
    }

    function truncateHtml($html, $limit = 100) {
        $text = strip_tags($html); // Quita las etiquetas HTML
        $truncated = Str::limit($text, $limit); // Aplica el límite
        return $truncated;
    }

    public function index_logeado()
    {
        $actividades = actividades::orderBy('created_at', 'desc')->get();
        
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
            ]);

        try {
            $actividad = new actividades();

            $actividad->id_usu = Auth::id();
            $actividad->titulo = $request->titulo;
            $actividad->cuerpo = $request->cuerpo;
            $actividad->noticia = $request->noticia;

            $actividad->save();

            $actividades = actividades::orderBy('created_at', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            //return view("actividades.index", compact('actividades'));
            return redirect()->route('actividades.auth')->with('success', 'Actividad creada exitosamente');

            /* return response()->json([
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

            $actividades = actividades::orderBy('created_at', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            return redirect()->route('actividades.auth')->with('error', 'Hubo un error al crear la actividad');
        }
    }

    public function show(string $id)
    {
        $actividad = actividades::find($id);

        return view("paginas_publicas.actividades_red", compact('actividad'));
    }

    public function edit(string $id)
    {
        $actividad = actividades::find($id);

        return view("actividades.edit", compact('actividad'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'cuerpo' => 'string',
            'noticia' => 'required|boolean',
        ]);

        try {
            $actividad = actividades::find($id);

            if (!$actividad) {
                return response()->json(['message' => 'Actividad no encontrada'], 404);
            }

            $actividad->titulo = $request->titulo;
            $actividad->cuerpo = $request->cuerpo;
            $actividad->noticia = $request->noticia;

            $actividad->save();

            $actividades = actividades::orderBy('created_at', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            //return view("actividades.index", compact('actividades'));
            return redirect()->route('actividades.auth')->with('success', 'Actividad actualizada exitosamente');

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

            $actividades = actividades::orderBy('created_at', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            return redirect()->route('actividades.auth')->with('error', 'Hubo un error al actualizar la actividad');
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

            $actividades = actividades::orderBy('created_at', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            //return view("actividades.index", compact('actividades'));
            return redirect()->route('actividades.auth')->with('success', 'Actividad eliminada exitosamente');

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

            $actividades = actividades::orderBy('created_at', 'desc')->get();
        
            foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
            }

            return redirect()->route('actividades.auth')->with('error', 'Hubo un error al eliminar la actividad');
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
