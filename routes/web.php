<?php

use App\Http\Controllers\Settings;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VariedadesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
    Route::put('settings/appearance', [Settings\AppearanceController::class, 'update'])->name('settings.appearance.update');
    Route::get('/inventario', [ProductoController::class, 'index'])->name('Productos_vista.index');

    /// V Ruta de  Variedad //

    Route::get('/variedad',[VariedadesController::class,'index'])->name('Variedad.index');
    Route::patch('variedades/{variedad}/toggle', [VariedadesController::class, 'toggle'])->name('variedades.toggle');
// GET = "muéstrame algo". Cuando el usuario da clic en "Nueva variedad",
// el navegador pide esta URL y Laravel llama al método create() del controller.
Route::get('/variedades/crear', [VariedadesController::class, 'create'])->name('Variedad.create');

// POST = "te mando datos". Cuando el usuario pica "Guardar variedad",
// el formulario manda todo aquí y Laravel llama al método store().
// El ->name() es el apodo de la ruta: por eso en las vistas escribes
// route('Variedad.store') en vez de la URL a mano — si mañana cambias
// la URL, los links no se rompen.
Route::post('/variedades', [VariedadesController::class, 'store'])->name('Variedad.store');

Route::get('/variedades/{variedad}/editar', [VariedadesController::class, 'edit'])->name('Variedad.edit');
Route::put('/variedades/{variedad}', [VariedadesController::class, 'update'])->name('Variedad.update');


});

require __DIR__.'/auth.php';
