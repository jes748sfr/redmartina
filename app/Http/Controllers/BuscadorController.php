<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BuscadorController extends Controller
{
    public function search(Request $request){
        // Validar el término de búsqueda
        $request->validate([
            'keyword' => 'required|string|min:1',
        ]);

        // Obtener el término de búsqueda
        $query = $request->input('keyword');

        // Realizar la búsqueda con Scout
        //$resultados = actividades::search($query)->get();

        // Buscar en la tabla 'actividades'
        $actividades = \App\Models\actividades::search($query)->get();
        $martianas = \App\Models\martianas::search($query)->get();
        $convocatorias = \App\Models\convocatoria::search($query)->get();

        foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
        }

        foreach ($martianas as $martina) {
            $martina->cuerpo_truncado = $this->truncateHtml($martina->cuerpo, 100);
        }

        foreach ($convocatorias as $convocatoria) {
            $convocatoria->cuerpo_truncado = $this->truncateHtml($convocatoria->cuerpo, 100);
        }

        $resultados = [
            'actividades' => $actividades,
            'martianas' => $martianas,
            'convocatorias' => $convocatorias,
        ];

        //$totalResultados = $resultados->count();
        $totalResultados = $actividades->count() + $martianas->count() + $convocatorias->count();

        // return response()->json([
        //     'success' => true,
        //     'data' => $resultados,
        //     'message' => 'Resultados encontrados exitosamente',
        // ], 200);

        // Retornar los resultados a la vista (o como JSON)
        return view('paginas_publicas.busqueda_publica', compact('resultados', 'query', 'totalResultados'));
    }

    function truncateHtml($html, $limit = 100) {
        $text = strip_tags($html); // Quita las etiquetas HTML
        $truncated = Str::limit($text, $limit); // Aplica el límite
        return $truncated;
    }
}
