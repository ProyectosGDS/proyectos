<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\temporalidad;
use Illuminate\Http\Request;

class TemporalidadController extends Controller
{
    public function index () {
        try {
            
            $temporalidades = temporalidad::get();

            return response($temporalidades);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store (Request $request) {

        $request->validate([
            'id_clase' => 'required',
            'id_curso' => 'required',
            'descripcion' => 'required|string|max:50',
        ]);

        try {
            
            temporalidad::create([
                'descripcion' => mb_strtoupper($request->descripcion),
                'id_clase' => $request->id_clase,
                'id_curso' => $request->id_curso,
                'usuario' => 'ADM_GDS',
                'fechau' => now(),
            ]);

            return response('Temporalidad creada exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show (temporalidad $temporalidad) {
        try {
            return response($temporalidad);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update (Request $request, temporalidad $temporalidad) {

        $request->validate([
            'id_clase' => 'required',
            'id_curso' => 'required',
            'descripcion' => 'required|string|max:50',
        ]);

        try {
            
            $temporalidad->descripcion = mb_strtoupper($request->descripcion);
            $temporalidad->id_curso = $request->id_curso;
            $temporalidad->id_clase = $request->id_clase;

            $temporalidad->save();

            return response('Temporalidad modificada exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

}
