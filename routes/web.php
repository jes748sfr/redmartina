<?php

use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\MarianasController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

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

Route::get('actividades', [ActividadesController::class, 'index']);
Route::post('actividades/store', [ActividadesController::class, 'store']);
Route::put('actividades/update/{id}', [ActividadesController::class, 'update']);
Route::delete('actividades/delete/{id}', [ActividadesController::class, 'destroy']);

Route::get('convocatorias', [ConvocatoriaController::class, 'index']);
Route::post('convocatorias/store', [ConvocatoriaController::class, 'store']);
Route::put('convocatorias/update/{id}', [ConvocatoriaController::class, 'update']);
Route::delete('convocatorias/delete/{id}', [ConvocatoriaController::class, 'destroy']);

Route::get('marianas', [MarianasController::class, 'index']);
Route::post('marianas/store', [MarianasController::class, 'store']);
Route::put('marianas/update/{id}', [MarianasController::class, 'update']);
Route::delete('marianas/delete/{id}', [MarianasController::class, 'destroy']);

Route::post('search', [ActividadesController::class, 'search'])->name('buscar');

require __DIR__.'/auth.php';
