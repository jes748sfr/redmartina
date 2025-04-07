<?php

namespace App\Http\Controllers;

use App\Models\actividades;
use App\Models\fotos;
use App\Models\galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GaleriaController extends Controller
{
    //
    public function index()
    {
        $galerias = galeria::with('fotos')->orderBy('created_at', 'desc')->paginate(3);
        $noticias = actividades::where('noticia', true)
                         ->orderBy('created_at', 'desc')
                         ->take(3)
                         ->get();

        return view("paginas_publicas.galeria_publica", compact('galerias','noticias'));
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
            'descripcion.max' => 'El titulo de la galeria no debe sobrepasar los 255 caracteres.',
        ];

            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255|regex:/^(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ])[\wÁÉÍÓÚáéíóúÑñ\s.,&\-():;\'"]+$/u',
                'descripcion' => 'nullable|string|max:255',
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
                session()->flash('alert_type', 'error');
                    session()->flash('alert_message', 'Hubo un error al crear la galeria');
                    session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                    
                    return redirect()->route('crear_Galeria')
                                     ->withErrors($validator)
                                     ->withInput();
            }

        try {

            $galeria = new galeria();

            $galeria->id_usu = Auth::id();
            $galeria->titulo = $request->titulo;
            $galeria->descripcion = $request->descripcion;

            $galeria->save();

            $archivosGuardados = [];

            if ($request->hasFile('imagen')) {
                foreach ($request->file('imagen') as $imagen) {
                    $extension = $imagen->getClientOriginalExtension();
                    $nombreArchivo = 'imagen_' . uniqid() . '.' . $extension;
                    $ruta = Storage::disk('public')->path('galeria/');
                    $imagen->move($ruta, $nombreArchivo);

                    $documentacion = new fotos();
                    $documentacion->id_galeria = $galeria->id;
                    $documentacion->imagen = $nombreArchivo;
                    $documentacion->save();

                    $archivosGuardados[] = $documentacion;
                }
            }

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha creado la galeria correctamente!');
            return redirect()->route('galerias.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al crear la galeria');
            return redirect()->route('galerias.auth');
        }
    }

    public function edit(string $id)
    {
        $galeria = galeria::findOrFail($id);
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
            'descripcion.max' => 'El titulo de la galeria no debe sobrepasar los 255 caracteres.',
        ];

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255|regex:/^(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ])[\wÁÉÍÓÚáéíóúÑñ\s.,&\-():;\'"]+$/u',
            'descripcion' => 'nullable|string|max:255',
        ], $mensajes);

        if ($validator->fails()) {
            session()->flash('alert_type', 'error');
                session()->flash('alert_message', 'Hubo un error al actualizar la galeria');
                session()->flash('validation_errors', $validator->errors()->all()); // <-- Array de errores
                
                return redirect()->route('editar_Galeria', $id)
                                 ->withErrors($validator)
                                 ->withInput();
        }

        try {
            $galeria = galeria::find($id);

            if (!$galeria) {
                return response()->json(['message' => 'Galeria no encontrada'], 404);
            }

            $galeria->titulo = $request->titulo;
            $galeria->descripcion = $request->descripcion;

            $galeria->save();

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha actualizado la galeria correctamente!');
            return redirect()->route('galerias.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al actualizar la informacion de la actividad');
            return redirect()->route('galerias.auth');
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

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', '¡Se ha eliminado la galeria correctamente!');
            return redirect()->route('galerias.auth');

        } catch (\Exception $e) {

            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Hubo un error al eliminar la galeria');
            return redirect()->route('galerias.auth');
        }
        
    }

}
