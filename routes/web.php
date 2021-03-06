<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipamentoController;

Route::get('/', [EquipamentoController::class,'index']);

Route::resource('equipamentos', EquipamentoController::class);

# Editar
Route::get('/equipamentos/{equipamento}/edit', [EquipamentoController::class, 'edit']);

# Logs  
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('can:admin');
