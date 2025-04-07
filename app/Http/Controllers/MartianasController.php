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
    public function index()
    {
        $martianas = martianas::orderBy('fecha', 'desc')->paginate(5);
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();

        // Procesar el cuerpo de las noticias
        foreach ($martianas as $martiana) {
            $martiana->cuerpo_truncado = $this->truncateHtml($martiana->cuerpo, 500);
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
            ], $mensajes);

            if ($validator->fails()) {
                session()->flash('alert_type', 'error');
                    session()->flash('alert_message', 'Hubo un error al crear la actividad martiana');
                    session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                    
                    return redirect()->route('crear_Martiana')
                                     ->withErrors($validator)
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

            $AgregarFile = $request->agregar_file;

            if ($AgregarFile == 0) {

                session()->flash('alert_type', 'success');
                session()->flash('alert_message', '¡Se ha creado la actividad martiana correctamente!');
                return redirect()->route('martianas.auth');

            }else{
                return redirect()->route('documentacion_martiana.crear', ['id' => $martianaId]);
            }

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al crear la actividad martiana');
            return redirect()->route('martianas.auth');

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
        $martiana = martianas::findOrFail($id);

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
            session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Hubo un error al actualizar la actividad martiana');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('editar_Martiana',$id)
                                 ->withErrors($validator)
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

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha actualizado la informacion de la actividad martiana correctamente!');
            return redirect()->route('martianas.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al actualizar la informacion de la actividad martiana');
            return redirect()->route('martianas.auth');

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

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha eliminado la actividad martiana correctamente!');
            return redirect()->route('martianas.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al eliminar la actividad martiana');
            return redirect()->route('martianas.auth');
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
            session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Espera...');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('martianas.auth')
                                 ->withErrors($validator)
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
