<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\programas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramasController extends Controller
{
    public function index () {
        try {
            
            $programas = programas::with([
                'dependencia',
                'escuela' => function($query){
                    $query->where('estatus','A');
                },
                'niveles' => function($query){
                    $query->where('estatus','A');
                },
                'levels'
            ])->where('id_dependencia',auth()->user()->id_dependencia)
            ->latest('id_programa')
            ->get();

            return response($programas);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store (Request $request) {

        DB::connection('oracle_gds')->beginTransaction();

        $request->validate([
            'id_dependencia' => 'required',
            'nombre' => 'required|string|max:150',
        ]);

        try {
            
            $programa = programas::create([
                'id_dependencia' => $request->id_dependencia,
                'nombre' => mb_strtoupper($request->nombre),
                'estatus' => 'A',
                'usuario' => auth()->user()->cui,
                'fechau' => now(),
            ]);



            if (isset($request->escuela['id_escuela'])) {
                $programa->escuela()->create([
                    'id_escuela' => $request->escuela['id_escuela'],
                    'usuario' => auth()->user()->cui,
                    'estatus' => "A",
                    'fechau' => now()
                ]);
            }

            foreach ($request->niveles as $nivel) {
                $programa->niveles()->create([
                    'id_nivel' => $nivel,
                    'estatus' => 'A',
                    'usuario' => auth()->user()->cui,
                    'fechau' => now()
                ]);
            }

            DB::connection('oracle_gds')->commit();

            return response('Programa creado exitosamente');

        } catch (\Throwable $th) {
            DB::connection('oracle_gds')->rollBack();
            return response($th->getMessage());
        }
    }

    public function show (programas $programa) {

        try {

            return response($programa->load('levels'));

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function update (Request $request, programas $programa) {
         $request->validate([
            'id_dependencia' => 'required',
            'nombre' => 'required|string|max:150',
        ]);

        try {
            
            $programa->id_dependencia = $request->id_dependencia;
            $programa->nombre = mb_strtoupper($request->nombre);
            $programa->estatus = $request->estatus;
            $programa->save();

            if(isset($request->escuela['id_escuela'])) {
                $programa->escuela()->update([
                    'id_escuela' => $request->escuela['id_escuela'],
                    'estatus' => 'A'
                ]);
            } else {
                $programa->escuela()->update([
                    'estatus' => 'I'
                ]);
            }
            
            
            if (!empty($request->niveles)) {
                
                $nivelesExistentes = $programa->niveles()->pluck('id_nivel')->toArray();
                
                $nivelesRecibidos = collect($request->niveles);

                
                $programa->niveles()
                    ->whereIn('id_nivel', $nivelesRecibidos)
                    ->update(['estatus' => 'A']);

                
                $nuevosNiveles = $nivelesRecibidos->diff($nivelesExistentes);
                foreach ($nuevosNiveles as $nivel) {
                    $programa->niveles()->create([
                        'id_nivel' => $nivel,
                        'estatus' => 'A',
                        'usuario' => 'ADM_GDS',
                        'fechau' => now(),
                    ]);
                }
                
                $nivelesAInactivar = collect($nivelesExistentes)->diff($nivelesRecibidos);
                $programa->niveles()
                    ->whereIn('id_nivel', $nivelesAInactivar)
                    ->update(['estatus' => 'I']);
            } else {
                
                $programa->niveles()->update(['estatus' => 'I']);
            }


            

            return response('Programa modificado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy (programas $programa) {
        try {
            
        $programa->estatus = 'I';
        $programa->save();

        return response('Programa desactivado exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
