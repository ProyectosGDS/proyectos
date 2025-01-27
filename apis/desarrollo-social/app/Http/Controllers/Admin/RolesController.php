<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index() {
        try {
            
            $roles = roles::with(['permisos'])->latest('id_rol')->get();

            return response($roles);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    
    public function store(Request $request) {

        $request->validate([
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:255',
        ]);

        try {

            $role = roles::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion
            ]);

            
            $role->permisos()->sync($request->permisos);
            

            return response('Role creado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    

    
    public function update(Request $request, roles $role) {
        $request->validate([
            'nombre' => 'required|string|max:80',
            'descripcion' => 'required|string|max:255',
        ]);

        try {

            $role->nombre = $request->nombre;
            $role->descripcion = $request->descripcion;
            $role->save();

            $role->permisos()->sync($request->permisos);

            return response('Role actualizado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    
    public function destroy(roles $role) {
        try {

            $role->delete();

            return response('Role eliminado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
