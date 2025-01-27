<?php

use App\Http\Controllers\ParticipacionCiudadana\CursosController;
use App\Http\Controllers\ParticipacionCiudadana\EventosController;
use Illuminate\Support\Facades\Route;


Route::get('participacion-ciudadana/eventos',[EventosController::class,'index']);
Route::get('participacion-ciudadana',[CursosController::class,'index']);
Route::get('participacion-ciudadana/{clase}',[CursosController::class,'show']);

