<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\requisitos;
use Illuminate\Http\Request;

class RequisitosController extends Controller
{
    public function index () {
        try {
            
            $requisitos = requisitos::latest('id_requisito')->get();

            return response($requisitos);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store (Request $request) {

        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        try {
            
            requisitos::create([
                'nombre' => mb_strtoupper($request->nombre),
                'estatus' => 'A',
                'usuario' => 'ADM_GDS',
                'fechau' => now(),
            ]);

            return response('Requisito creado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show (requisitos $requisito) {
        try {
            return response($requisito);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update (Request $request, requisitos $requisito) {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        try {
            
            $requisito->nombre = mb_strtoupper($request->nombre);
            $requisito->estatus = $request->estatus;

            $requisito->save();

            return response('Requisito modificado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy (requisitos $requisito) {
        try {
            
        $requisito->estatus = 'I';
        $requisito->save();

        return response('Requisito desactivado exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
