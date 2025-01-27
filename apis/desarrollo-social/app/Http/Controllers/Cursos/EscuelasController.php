<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\escuelas;
use Illuminate\Http\Request;

class EscuelasController extends Controller
{
    public function index () {
        try {
            
            $escuelas = escuelas::get();

            return response($escuelas);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store (Request $request) {

        $request->validate([
            'nombre' => 'required|string|max:150',
        ]);

        try {
            
            escuelas::create([
                'nombre' => mb_strtoupper($request->nombre),
                'usuario' => 'ADM_GDS',
                'fechau' => now(),
            ]);

            return response('Escuela creada exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show (escuelas $escuela) {
        try {
            return response($escuela);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update (Request $request, escuelas $escuela) {
        $request->validate([
            'nombre' => 'required|string|max:150',
        ]);

        try {
            
            $escuela->nombre = mb_strtoupper($request->nombre);

            $escuela->save();

            return response('Escuela modificada exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
