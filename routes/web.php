<?php

use App\Http\Controllers\ReceitaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('receitas/exportar/pdf', [ReceitaController::class, 'exportarPdf'])->name('receitas.exportar.pdf');
    Route::resource('receitas', ReceitaController::class);
});

require __DIR__ . '/auth.php';
