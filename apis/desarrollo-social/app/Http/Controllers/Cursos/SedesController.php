<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\sedes;
use Illuminate\Http\Request;

class SedesController extends Controller
{
    public function index () {
        try {
            
            $sedes = sedes::with(['zona'])->orderBy('descripcion')->get();

            return response($sedes);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store (Request $request) {

        $request->validate([
            'id_zona' => 'required',
            'descripcion' => 'required|string|max:100',
            'direccion' => 'required|string|max:150',
        ]);

        try {
            
            sedes::create([
                'descripcion' => mb_strtoupper($request->descripcion),
                'id_zona' => $request->id_zona,
                'direccion' => $request->direccion,
                'distrito' => $request->distrito ?? null,
                'estatus' => 'A',
                'usuario' => 'ADM_GDS',
                'fechau' => now(),
            ]);

            return response('Sede creada exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show (sedes $sede) {
        try {
            return response($sede);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update (Request $request, sedes $sede) {
        $request->validate([
            'id_zona' => 'required',
            'descripcion' => 'required|string|max:100',
            'direccion' => 'required|string|max:150',
        ]);

        try {
            
            $sede->descripcion = mb_strtoupper($request->descripcion);
            $sede->id_zona = $request->id_zona;
            $sede->direccion = $request->direccion;
            $sede->distrito = $request->distrito ?? null;
            $sede->estatus = $request->estatus;

            $sede->save();

            return response('Sede modificada exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy (sedes $sede) {
        try {
            
        $sede->estatus = 'I';
        $sede->save();

        return response('Sede desactivada exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
