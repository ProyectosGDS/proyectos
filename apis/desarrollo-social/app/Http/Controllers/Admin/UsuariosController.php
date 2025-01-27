<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\usuarios;
use App\Rules\ValidateCui;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuariosController extends Controller
{

    public function index() {
        try {
            $usuarios = usuarios::with(['role','dependencia'])->get();
            return response($usuarios);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store(Request $request) {
        $request->validate([
            'cui' => ['required','numeric','digits:13',new ValidateCui,'unique:usuarios,cui'],
            'nombre' => 'required|string|max:150',
            'id_dependencia' => 'required'
        ]);

        try {

            $year = date('Y');
            
            $usuario = usuarios::create([
                'cui' => $request->cui,
                // 'password' => Hash::make('MuniGuateGDS'.$year),
                'password' => Hash::make('muniguate'.$year),
                'nombre' => mb_strtoupper(trim($request->nombre)),
                'id_dependencia' => $request->id_dependencia,
                'id_rol' => $request->id_rol ?? null
            ]);



            return response('Usuario creado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }


    public function show(usuarios $usuario) {
        try {
            return response(
                $usuario->load([
                    'role',
                    'menus'
                ])
            );

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }


    public function update(Request $request, usuarios $usuario) {
        
        $request->validate([
            'cui' => ['required','numeric','digits:13', new ValidateCui, Rule::unique('usuarios', 'cui')->ignore($usuario->id_usuario,'id_usuario')],
            'nombre' => 'required|string|max:150',
            'id_dependencia' => 'required'
        ]);

        try {

            $usuario->cui = $request->cui;
            $usuario->nombre = mb_strtoupper(trim($request->nombre));
            $usuario->id_dependencia = $request->id_dependencia;
            $usuario->id_rol = $request->id_rol ?? null;
            $usuario->save();

            return response('Usuario modificado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy(usuarios $usuario) {

        try {

            $usuario->delete();
            return response('Usuario eliminado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    function restartPassword(usuarios $usuario) {
        try {

            $year = date('Y');
            // $usuario->password = Hash::make('MuniGuateGDS'.$year);
            $usuario->password = Hash::make('muniguate'.$year);
            $usuario->save();

            return response('Contraseña reiniciada con exito');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    function asignPages(Request $request, usuarios $usuario) {
        $request->validate([
            'paginas' => 'required|array'
        ]);

        try {
            
            $usuario->menus()->sync($request->paginas);
            return response('Paginas asignadas correctamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

}
