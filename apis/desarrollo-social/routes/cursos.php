<?php

use App\Http\Controllers\Cursos\ClasesController;
use App\Http\Controllers\Cursos\CursosController;
use App\Http\Controllers\Cursos\EscuelasController;
use App\Http\Controllers\Cursos\HorariosController;
use App\Http\Controllers\Cursos\InstructoresController;
use App\Http\Controllers\Cursos\NivelesController;
use App\Http\Controllers\Cursos\ProgramasController;
use App\Http\Controllers\Cursos\RequisitosController;
use App\Http\Controllers\Cursos\SedesController;
use App\Http\Controllers\Cursos\TemporalidadController;
use Illuminate\Support\Facades\Route;

Route::apiResource('programas',ProgramasController::class);
Route::apiResource('cursos',CursosController::class);


Route::post('clases/clase',[ClasesController::class,'getClase']);
Route::post('clases/clases',[ClasesController::class,'getClases']);

Route::apiResource('clases',ClasesController::class);

Route::apiResource('niveles',NivelesController::class)->except(['show']);
Route::apiResource('escuelas',EscuelasController::class)->except(['show','destroy']);

Route::apiResource('instructores',InstructoresController::class)->except(['show']);
Route::apiResource('sedes',SedesController::class)->except(['show']);
Route::apiResource('temporalidad',TemporalidadController::class)->except(['show']);
Route::apiResource('horarios',HorariosController::class)->except(['show']);
Route::apiResource('requisitos',RequisitosController::class);