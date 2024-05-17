<?php

use App\Http\Controllers\DocController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/adicionar-documentos', [DocController::class, 'adicionarDocumentos']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DocController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/doc/{docUrl}', [DocController::class, 'DOC_Temporario']);
    Route::post('/busca', [DocController::class, 'busca']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';