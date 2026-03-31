<?php

use App\Http\Controllers\ReceitaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::resource('receitas', ReceitaController::class);
});

require __DIR__.'/auth.php';