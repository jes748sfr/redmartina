<?php

use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\BuscadorController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\MartianasController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
});

Route::get('convocatorias', [ConvocatoriaController::class, 'index'])->name('convocatorias');
Route::post('convocatorias/store', [ConvocatoriaController::class, 'store']);
Route::put('convocatorias/update/{id}', [ConvocatoriaController::class, 'update']);
Route::delete('convocatorias/delete/{id}', [ConvocatoriaController::class, 'destroy']);

Route::get('martianas', [MartianasController::class, 'index'])->name('martianas');
Route::post('martianas/store', [MartianasController::class, 'store']);
Route::put('martianas/update/{id}', [MartianasController::class, 'update']);
Route::delete('martianas/delete/{id}', [MartianasController::class, 'destroy']);

Route::get('galeria', [GaleriaController::class, 'index'])->name('galeria');

Route::post('search', [BuscadorController::class, 'search'])->name('buscar');

Route::post('search/actividad', [ActividadesController::class, 'search_actividad'])->name('buscar_actividad');

require __DIR__.'/auth.php';
