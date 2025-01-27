<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\niveles;
use Illuminate\Http\Request;

class NivelesController extends Controller
{
    public function index() {
        try {
            
            $niveles = niveles::latest('id_nivel')->get();

            return response($niveles);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store(Request $request) {

        $request->validate([
            'descripcion' => 'required|string|max:20',
        ]);

        try {
            
            niveles::create([
                'descripcion' => mb_strtoupper($request->descripcion),
                'estatus' => 'A',
                'usuario' => 'ADM_GDS',
                'fechau' => now(),
            ]);

            return response('Nivel creado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show(niveles $nivel) {
        try {
            return response($nivel);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update( niveles $nivele, Request $request) {
        $request->validate([
            'descripcion' => 'required|string|max:20',
        ]);

        try {
            
            $nivele->descripcion = mb_strtoupper($request->descripcion);
            $nivele->estatus = $request->estatus;

            $nivele->save();

            return response('Nivel modificado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy(niveles $nivele) {
        try {
            
        $nivele->estatus = 'I';
        $nivele->save();

        return response('Nivel desactivado exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
