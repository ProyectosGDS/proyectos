<?php

namespace App\Http\Controllers\Beneficiarios;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\parentescos;
use Illuminate\Http\Request;

class ParentescoController extends Controller
{
    public function index() {
        try {
            
            $parentescos = parentescos::latest('id_parentesco')->get();

            return response($parentescos);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store(Request $request) {

        $request->validate([
            'descripcion' => 'required|string|max:50',
        ]);

        try {
            
            parentescos::create([
                'descripcion' => $request->descripcion,
            ]);

            return response('Parentesco creado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show(parentescos $parentesco) {
        try {
            return response($parentesco);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update( parentescos $parentesco, Request $request) {
        $request->validate([
            'descripcion' => 'required|string|max:50',
        ]);

        try {
            
            $parentesco->descripcion = $request->descripcion;
            $parentesco->save();

            return response('Parentesco modificado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy(parentescos $parentesco) {
        try {
            
        $parentesco->delete();

        return response('Parentesco elminado exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
