<?php

namespace App\Http\Controllers\Beneficiarios;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\enfermedades;
use Illuminate\Http\Request;

class EnfermedadesController extends Controller
{
    public function index() {
        try {
            
            $enfermedades = enfermedades::latest('id_enfermedad')->get();

            return response($enfermedades);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store(Request $request) {

        $request->validate([
            'descripcion' => 'required|string|max:50',
        ]);

        try {
            
            enfermedades::create([
                'descripcion' => $request->descripcion,
            ]);

            return response('enfermedad creado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show(enfermedades $enfermedade) {
        try {
            return response($enfermedade);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update( enfermedades $enfermedade, Request $request) {
        $request->validate([
            'descripcion' => 'required|string|max:50',
        ]);

        try {
            
            $enfermedade->descripcion = $request->descripcion;
            $enfermedade->save();

            return response('enfermedad modificado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy(enfermedades $enfermedade) {
        try {
            
        $enfermedade->delete();

        return response('enfermedad elminado exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
