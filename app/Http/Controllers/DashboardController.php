<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\convocatoria;
use App\Models\martianas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();

        $actividades = actividades::where('noticia', false)
                         ->orderBy('fecha', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();

        foreach ($actividades as $actividad) {
            $actividad->cuerpo_truncado = $this->truncateHtml($actividad->cuerpo, 100);
        }

        $martianas = martianas::orderBy('fecha', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();

        foreach ($martianas as $martiana) {
            $martiana->cuerpo_truncado = $this->truncateHtml($martiana->cuerpo, 100);
        }

        $convocatorias = convocatoria::orderBy('fecha', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();

        foreach ($convocatorias as $convocatoria) {
            $convocatoria->cuerpo_truncado = $this->truncateHtml($convocatoria->cuerpo, 100);
        }

        return view("dashboard", compact('actividades','martianas', 'convocatorias','noticias'));
        
    }

    function truncateHtml($html, $limit = 100) {
        $text = strip_tags($html); // Quita las etiquetas HTML
        $truncated = Str::limit($text, $limit); // Aplica el límite
        return $truncated;
    }

}
