<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\clases_x_cusos;
use App\Models\adm_gds\cursos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CursosController extends Controller
{
    
    public function index () {
        try {
            
            $cursos = cursos::all();

            return response($cursos);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store (Request $request) {
        
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:800',
        ]);

        try {

            $curso = cursos::create([
                'nombre' => mb_strtoupper($request->nombre),
                'descripcion' => $request->descripcion,
                'estatus' => 'A',
                'usuario' => auth()->user()->cui,
                'fechau' => now(),
            ]);

            return response('Curso creado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show (cursos $curso) {
        try {

            return response($curso);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update (Request $request, cursos $curso) {
         $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:800',
        ]);

        try {
            
            $curso->nombre = mb_strtoupper($request->nombre);
            $curso->descripcion = $request->descripcion;
            $curso->estatus = $request->estatus;

            $curso->save();

            return response('Curso modificado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy (cursos $curso) {
        try {
            
            $curso->estatus = 'I';
            $curso->save();

            return response('Curso desactivado exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
