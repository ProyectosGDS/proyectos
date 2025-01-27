<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\horarios;
use Illuminate\Http\Request;

class HorariosController extends Controller
{
    public function index () {
        try {
            
            $horarios = horarios::latest('id_horario')->get();

            return response($horarios);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store (Request $request) {

        $request->validate([
            'hora_ini' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_ini',
        ]);

        try {

            $dias = [];

            if($request->dias) {
                foreach ($request->dias as $dia) {
                    $dias[$dia] = $dia;
                }
            }

            horarios::create([
                'hora_ini' => $request->hora_ini,
                'hora_fin' => $request->hora_fin,
                'lun' => isset($dias['LUN']) ? $dias['LUN'] : null,
                'mar' => isset($dias['MAR']) ? $dias['MAR'] : null,
                'mie' => isset($dias['MIE']) ? $dias['MIE'] : null,
                'jue' => isset($dias['JUE']) ? $dias['JUE'] : null,
                'vie' => isset($dias['VIE']) ? $dias['VIE'] : null,
                'sab' => isset($dias['SAB']) ? $dias['SAB'] : null,
                'dom' => isset($dias['DOM']) ? $dias['DOM'] : null,
                'estatus' => 'A',
                'usuario' => 'ADM_GDS',
                'fechau' => now(),
            ]);

            return response('Horario creado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show (horarios $horario) {
        try {
            return response($horario);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update (Request $request, horarios $horario) {

        $request->validate([
            'hora_ini' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_ini',
        ]);

        $dias = [];

            if($request->dias) {
                foreach ($request->dias as $dia) {
                    $dias[$dia] = $dia;
                }
            }

        try {
            
            
            $horario->hora_ini = $request->hora_ini;
            $horario->hora_fin = $request->hora_fin;
            $horario->lun = isset($dias['LUN']) ? $dias['LUN'] : null;
            $horario->mar = isset($dias['MAR']) ? $dias['MAR'] : null;
            $horario->mie = isset($dias['MIE']) ? $dias['MIE'] : null;
            $horario->jue = isset($dias['JUE']) ? $dias['JUE'] : null;
            $horario->vie = isset($dias['VIE']) ? $dias['VIE'] : null;
            $horario->sab = isset($dias['SAB']) ? $dias['SAB'] : null;
            $horario->dom = isset($dias['DOM']) ? $dias['DOM'] : null;
            $horario->estatus = $request->estatus;
            
            $horario->save();

            return response('Horario modificado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy (horarios $horario) {
        try {
            
        $horario->estatus = 'I';
        $horario->save();

        return response('Horario desactivado exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
