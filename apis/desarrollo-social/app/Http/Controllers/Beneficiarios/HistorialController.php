<?php

namespace App\Http\Controllers\Beneficiarios;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\historial_personas;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index () {
        try {
            $historial = historial_personas::with(['persona'])->get();
            return response($historial);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
    
    public function show () {
        try {
            return response();
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store (Request $request) {
        $request->validate([
            'id_persona' => 'required',
            'accion' => 'required|string|max:100',
            'descripcion' => 'required|string|max:500',
        ]);

        try {
            
            $historial = historial_personas::create([
                'id_persona' => $request->id_persona,
                'accion' => mb_strtoupper($request->accion),
                'descripcion' => $request->descripcion,
                'usuario' => auth()->user()->cui,
                'fechau' => now()
            ]);

            return response('Registro creado exitosamente');
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update (Request $request) {
        try {
            return response();
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
    public function destroy () {
        try {
            return response();
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
