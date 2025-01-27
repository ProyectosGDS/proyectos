<?php

use App\Http\Controllers\Beneficiarios\BeneficiarioController;
use App\Http\Controllers\Beneficiarios\HistorialController;
use Illuminate\Support\Facades\Route;


Route::get('beneficiarios/catalogos',[BeneficiarioController::class,'catalogos']);
Route::get('beneficiarios/bitacoras/{beneficiario}',[BeneficiarioController::class,'bitacoras']);
Route::post('beneficiarios/consulta-back-up',[BeneficiarioController::class,'consultaBackUp']);
Route::post('beneficiarios/asignar-curso/{persona}',[BeneficiarioController::class,'asignarCurso']);
Route::apiResource('beneficiarios',BeneficiarioController::class);
Route::apiResource('historial',HistorialController::class)->only(['index','store']);

