<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\instructores;
use Illuminate\Http\Request;

class InstructoresController extends Controller
{
    public function index () {
        try {
            
            $instructores = instructores::orderBy('nombre')->get();

            return response($instructores);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store (Request $request) {

        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        try {
            
            instructores::create([
                'nombre' => mb_strtoupper($request->nombre),
                'estatus' => 'A',
                'usuario' => 'ADM_GDS',
                'fechau' => now(),
            ]);

            return response('Instructor creado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show (instructores $instructor) {
        try {
            return response($instructor);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update (Request $request, instructores $instructore) {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        try {
            
            $instructore->nombre = mb_strtoupper($request->nombre);
            $instructore->estatus = $request->estatus;

            $instructore->save();

            return response('Instructor modificado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy (instructores $instructore) {
        try {
            
        $instructore->estatus = 'I';
        $instructore->save();

        return response('Instructor desactivado exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
