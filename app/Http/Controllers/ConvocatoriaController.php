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
    public function index()
    {
        $convocatorias = convocatoria::orderBy('fecha', 'desc')->paginate(5);
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')  // Asegúrate de que el campo 'fecha' esté en tu base de datos
                         ->take(3)  // Limitar a 3 resultados
                         ->get();

        // Procesar el cuerpo de las noticias
        foreach ($convocatorias as $convocatoria) {
            $convocatoria->cuerpo_truncado = $this->truncateHtml($convocatoria->cuerpo, 500);
        }

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
                session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Hubo un error al crear la convocatoria');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('crear_Convocatoria')
                                 ->withErrors($validator)
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

            $AgregarFile = $request->agregar_file;

            if ($AgregarFile == 0) {

                session()->flash('alert_type', 'success');
                session()->flash('alert_message', '¡Se ha creado la convocatoria correctamente!');
                return redirect()->route('convocatorias.auth');

            }else{
                return redirect()->route('documentacion_convocatoria.crear', ['id' => $convocatoriaId]);
            }

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al crear la convocatoria');
            return redirect()->route('convocatorias.auth');

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
        $convocatoria = convocatoria::findOrFail($id);

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
            ],$mensajes);


        if ($validator->fails()) {
            session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Hubo un error al actualizar la actividad');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('editar_Convocatoria',$id)
                                 ->withErrors($validator)
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

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha actualizado la informacion de la convocatoria correctamente!');
            return redirect()->route('convocatorias.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al actualizar la informacion de la convocatoria');
            return redirect()->route('convocatorias.auth');

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

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha eliminado la convocatoria correctamente!');
            return redirect()->route('convocatorias.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al eliminar la convocatoria');
            return redirect()->route('convocatorias.auth');

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
                session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Espera...');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('convocatorias.auth')
                                 ->withErrors($validator)
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
