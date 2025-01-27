<?php

use App\Http\Controllers\Admin\DependenciasController;
use App\Http\Controllers\Admin\MenuPaginasController;
use App\Http\Controllers\Admin\PermisosController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsuariosController;
use Illuminate\Support\Facades\Route;


Route::apiResource('permisos',PermisosController::class);
Route::apiResource('roles',RolesController::class);
Route::apiResource('usuarios',UsuariosController::class);
Route::apiResource('dependencias',DependenciasController::class)->except(['show','destroy']);
Route::apiResource('menu-paginas',MenuPaginasController::class)->except(['show','destroy']);

Route::put('usuarios/asignar-paginas/{usuario}',[UsuariosController::class,'asignPages']);
Route::put('usuarios/reiniciar-password/{usuario}',[UsuariosController::class,'restartPassword']);