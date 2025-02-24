<?php

use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\BuscadorController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorioController;
use App\Http\Controllers\DocumentacionActividadesController;
use App\Http\Controllers\DocumentacionConvocatoriasController;
use App\Http\Controllers\DocumentacionMartianasController;
use App\Http\Controllers\FotosController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\MartianasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/index', function () {
//     return view('index');
// });

Route::get('/', [ActividadesController::class, 'inicio']);

Route::get('actividades', [ActividadesController::class, 'index'])->name('actividades');

Route::get('actividades/show/{id}', [ActividadesController::class, 'show'])->name('visualizar_actividades');

Route::middleware('auth')->group(function () {
    Route::get('actividades/crear', [ActividadesController::class, 'create'])->name('crear_Actividad');
    Route::post('actividades/store', [ActividadesController::class, 'store'])->name('actividades.store');
    Route::get('actividades/edit/{id}', [ActividadesController::class, 'edit'])->name('editar_Actividad');
    Route::put('actividades/update/{id}', [ActividadesController::class, 'update'])->name('actividades.update');
    Route::delete('actividades/delete/{id}', [ActividadesController::class, 'destroy'])->name('actividades.delete');

    Route::get('actividades/auth', [ActividadesController::class, 'index_logeado'])->name('actividades.auth');

    Route::get('actividades/subir_archivos/{id}', [DocumentacionActividadesController::class, 'create'])->name('documentacion_actividad.crear');
    Route::post('actividades/subir_archivos/store', [DocumentacionActividadesController::class, 'store_img'])->name('documentacion_actividad.store');
    Route::post('actividades/subir_archivos/edicion/store', [DocumentacionActividadesController::class, 'store_2'])->name('documentacion_actividad.store2');
    Route::get('actividades/subir_archivos/edit/{id}', [DocumentacionActividadesController::class, 'edit'])->name('documentacion_actividad.edit');
    Route::put('actividades/subir_archivos/update/{id}', [DocumentacionActividadesController::class, 'update'])->name('documentacion_actividad.update');
    Route::delete('actividades/subir_archivos/delete/{id}', [DocumentacionActividadesController::class, 'destroy'])->name('documentacion_actividad.delete');
});

Route::get('convocatorias', [ConvocatoriaController::class, 'index'])->name('convocatorias');

Route::get('convocatorias/show/{id}', [ConvocatoriaController::class, 'show'])->name('visualizar_convocatorias');

Route::middleware('auth')->group(function () {
    Route::get('convocatorias/crear', [ConvocatoriaController::class, 'create'])->name('crear_Convocatoria');
    Route::post('convocatorias/store', [ConvocatoriaController::class, 'store'])->name('convocatorias.store');
    Route::get('convocatorias/edit/{id}', [ConvocatoriaController::class, 'edit'])->name('editar_Convocatoria');
    Route::put('convocatorias/update/{id}', [ConvocatoriaController::class, 'update'])->name('convocatorias.update');
    Route::delete('convocatorias/delete/{id}', [ConvocatoriaController::class, 'destroy'])->name('convocatorias.delete');

    Route::get('convocatorias/auth', [ConvocatoriaController::class, 'index_logeado'])->name('convocatorias.auth');

    Route::get('convocatorias/subir_archivos/{id}', [DocumentacionConvocatoriasController::class, 'create'])->name('documentacion_convocatoria.crear');
    Route::post('convocatorias/subir_archivos/store', [DocumentacionConvocatoriasController::class, 'store_img'])->name('documentacion_convocatoria.store');
    Route::post('convocatorias/subir_archivos/edicion/store', [DocumentacionConvocatoriasController::class, 'store_2'])->name('documentacion_convocatoria.store2');
    Route::get('convocatorias/subir_archivos/edit/{id}', [DocumentacionConvocatoriasController::class, 'edit'])->name('documentacion_convocatoria.edit');
    Route::put('convocatorias/subir_archivos/update/{id}', [DocumentacionConvocatoriasController::class, 'update'])->name('documentacion_convocatoria.update');
    Route::delete('convocatorias/subir_archivos/delete/{id}', [DocumentacionConvocatoriasController::class, 'destroy'])->name('documentacion_convocatoria.delete');
});

Route::get('martianas', [MartianasController::class, 'index'])->name('martianas');

Route::get('martianas/show/{id}', [MartianasController::class, 'show'])->name('visualizar_martianas');

Route::middleware('auth')->group(function () {
    Route::get('martianas/crear', [MartianasController::class, 'create'])->name('crear_Martiana');
    Route::post('martianas/store', [MartianasController::class, 'store'])->name('martianas.store');
    Route::get('martianas/edit/{id}', [MartianasController::class, 'edit'])->name('editar_Martiana');
    Route::put('martianas/update/{id}', [MartianasController::class, 'update'])->name('martianas.update');
    Route::delete('martianas/delete/{id}', [MartianasController::class, 'destroy'])->name('martianas.delete');

    Route::get('martianas/auth', [MartianasController::class, 'index_logeado'])->name('martianas.auth');

    Route::get('martianas/subir_archivos/{id}', [DocumentacionMartianasController::class, 'create'])->name('documentacion_martiana.crear');
    Route::post('martianas/subir_archivos/store', [DocumentacionMartianasController::class, 'store_img'])->name('documentacion_martiana.store');
    Route::post('martianas/subir_archivos/edicion/store', [DocumentacionMartianasController::class, 'store_2'])->name('documentacion_martiana.store2');
    Route::get('martianas/subir_archivos/edit/{id}', [DocumentacionMartianasController::class, 'edit'])->name('documentacion_martiana.edit');
    Route::put('martianas/subir_archivos/update/{id}', [DocumentacionMartianasController::class, 'update'])->name('documentacion_martiana.update');
    Route::delete('martianas/subir_archivos/delete/{id}', [DocumentacionMartianasController::class, 'destroy'])->name('documentacion_martiana.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('galerias/crear', [GaleriaController::class, 'create'])->name('crear_Galeria');
    Route::post('galerias/store', [GaleriaController::class, 'store'])->name('galerias.store');
    Route::get('galerias/edit/{id}', [GaleriaController::class, 'edit'])->name('editar_Galeria');
    Route::put('galerias/update/{id}', [GaleriaController::class, 'update'])->name('galerias.update');
    Route::delete('galerias/delete/{id}', [GaleriaController::class, 'destroy'])->name('galerias.delete');

    Route::get('galerias/auth', [GaleriaController::class, 'index_logeado'])->name('galerias.auth');

    Route::get('galerias/subir_archivos/{id}', [FotosController::class, 'create'])->name('documentacion_galeria.crear');
    Route::post('galerias/subir_archivos/store', [FotosController::class, 'store_img'])->name('documentacion_galeria.store');
    Route::post('galerias/subir_archivos/edicion/store', [FotosController::class, 'store'])->name('documentacion_galeria.store2');
    Route::get('galerias/subir_archivos/edit/{id}', [FotosController::class, 'edit'])->name('documentacion_galeria.edit');
    Route::put('galerias/subir_archivos/update/{id}', [FotosController::class, 'update'])->name('documentacion_galeria.update');
    Route::delete('galerias/subir_archivos/delete/{id}', [FotosController::class, 'destroy'])->name('documentacion_galeria.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('directorios/crear', [DirectorioController::class, 'create'])->name('crear_Directorio');
    Route::post('directorios/store', [DirectorioController::class, 'store'])->name('directorios.store');
    Route::get('directorios/edit/{id}', [DirectorioController::class, 'edit'])->name('editar_Directorio');
    Route::put('directorios/update/{id}', [DirectorioController::class, 'update'])->name('directorios.update');
    Route::delete('directorios/delete/{id}', [DirectorioController::class, 'destroy'])->name('directorios.delete');

    Route::get('directorios/auth', [DirectorioController::class, 'index_logeado'])->name('directorios.auth');
});


Route::get('galeria', [GaleriaController::class, 'index'])->name('galeria');

Route::get('directorio', [DirectorioController::class, 'index'])->name('directorio');

Route::post('search', [BuscadorController::class, 'search'])->name('buscar');

Route::match(['get', 'post'],'search/actividad', [ActividadesController::class, 'search_actividad'])->name('buscar_actividad');
Route::post('search/convocatoria', [ConvocatoriaController::class, 'search_convocatoria'])->name('buscar_convocatoria');
Route::post('search/martiana', [MartianasController::class, 'search_martiana'])->name('buscar_martiana');

Route::middleware('auth', 'role:Admin')->group(function () {
    Route::get('usuarios', [UserController::class, 'index'])->name('Ver_usuarios');
    Route::post('/usuarios/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('usuarios.toggleStatus');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/store', [UserController::class, 'store'])->name('usuarios.store');
});

Route::get('/creditos', function () {
    return view('paginas_publicas.creditos_publico');
})->name('creditos');

Route::get('/politicas', function () {
    return view('paginas_publicas.politicas_publico');
})->name('politicas');


require __DIR__.'/auth.php';
