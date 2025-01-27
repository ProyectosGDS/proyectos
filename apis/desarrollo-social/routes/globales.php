<?php

use App\Http\Controllers\Globales\DireccionesController;
use App\Http\Controllers\Globales\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('grupo-zona/{id_zona}/{id_grupo}',[DireccionesController::class,'grupo_zona']);
Route::get('zonas',[DireccionesController::class,'zonas']);

Route::post('exportar-excel',[ExportController::class,'exportExcel'])->middleware(['jwtAuth']);