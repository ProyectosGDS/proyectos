<?php

use App\Http\Controllers\Beneficiarios\BeneficiarioController;
use App\Http\Controllers\Beneficiarios\EnfermedadesController;
use App\Http\Controllers\Beneficiarios\HistorialController;
use App\Http\Controllers\Beneficiarios\ParentescoController;
use Illuminate\Support\Facades\Route;


Route::get('beneficiarios/catalogos',[BeneficiarioController::class,'catalogos']);
Route::get('beneficiarios/bitacoras/{beneficiario}',[BeneficiarioController::class,'bitacoras']);
Route::post('beneficiarios/consulta-back-up',[BeneficiarioController::class,'consultaBackUp']);
Route::post('beneficiarios/asignar-curso/{persona}',[BeneficiarioController::class,'asignarCurso']);
Route::post('get-beneficiarios',[BeneficiarioController::class,'getBeneficiarios']);

Route::apiResource('beneficiarios',BeneficiarioController::class);
Route::apiResource('historial',HistorialController::class)->only(['index','store']);

Route::apiResource('parentescos',ParentescoController::class);
Route::apiResource('enfermedades',EnfermedadesController::class);

