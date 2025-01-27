<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\clases_x_cusos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClasesController extends Controller
{
    public function index () {
        try {
            
            $query = "
                SELECT
                    CC.ID_CLASE,
                    I.NOMBRE INSTRUCTOR,
                    T.DESCRIPCION TEMPORALIDAD,
                    S.DESCRIPCION SEDE,
                    C.NOMBRE CURSO,
                    P.NOMBRE PROGRAMA,
                    N.DESCRIPCION NIVEL,
                    CC.MODALIDAD,
                    CC.SECCION,
                    CC.CAPACIDAD,
                    CC.ESTATUS,
                    CC.FECHAU,
                    H.HORA_INI||' A '||H.HORA_FIN ||' '|| ConcatenarDias(LUN, MAR, MIE, JUE, VIE, SAB, DOM) AS FULL
                FROM CLASES_X_CURSO CC
                    INNER JOIN INSTRUCTORES I
                        ON I.ID_INSTRUCTOR = CC.ID_INSTRUCTOR
                    INNER JOIN TEMPORALIDAD T
                        ON T.ID_TEMPORALIDAD = CC.ID_TEMPORALIDAD
                    INNER JOIN SEDES S
                        ON S.ID_SEDE = CC.ID_SEDE
                    INNER JOIN HORARIOS H
                        ON H.ID_HORARIO = CC.ID_HORARIO
                    INNER JOIN CURSOS C
                        ON C.ID_CURSO = CC.ID_CURSO
                    INNER JOIN PROGRAMAS P
                        ON P.ID_PROGRAMA = CC.ID_PROGRAMA
                    INNER JOIN NIVELES N
                        ON N.ID_NIVEL = CC.ID_NIVEL
                WHERE P.ID_DEPENDENCIA =  ?
                ORDER BY CC.ID_CLASE DESC
            ";

            $clases = DB::connection('oracle_gds')->select($query,[auth()->user()->id_dependencia]);

            return response($clases);

        } catch (\Throwable $th) {
            return response($th->getMessage(),422);
        }
    }

    public function store (Request $request) {
    
        $request->validate(
            [
                'id_curso' => 'required',
                'id_nivel' => 'required',
                'id_programa' => 'required',
                'clases' => 'required|array',
            ],
            [
                'clases.required' => 'Este curso no tiene elementos asignados'
            ]);

        try {
            
            foreach ($request->clases as $clase) {
                clases_x_cusos::create([
                    'id_curso' => $request->id_curso,
                    'id_nivel' => $request->id_nivel,
                    'id_programa' => $request->id_programa,
                    'id_instructor' => $clase['instructor']['id_instructor'],
                    'id_sede' => $clase['sede']['id_sede'],
                    'id_horario' => $clase['horario']['id_horario'],
                    'id_temporalidad' => $clase['temporalidad']['id_temporalidad'],
                    'seccion' => mb_strtoupper($clase['seccion']),
                    'capacidad' => $clase['capacidad'],
                    'modalidad' => $clase['modalidad'],
                    'estatus' => 'A',
                    'usuario' => auth()->user()->cui,
                    'fechau' => now(),
                ]);
            }
            
            return response('Clases creadas exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage(),422);
        }

    }

    public function show (clases_x_cusos $clase) {
        try {
            return response($clase->load([
                'programa',
                'nivel',
                'curso',
                'instructor',
                'temporalidad',
                'horario',
                'sede',
            ]));

        } catch (\Throwable $th) {
            return response($th->getMessage(),422);
        }
    }

    public function update (Request $request, int $clase) {

        $request->validate([
            'id_programa' => 'required',
            'id_nivel' => 'required',
            'id_curso' => 'required',
        ]);

        try {

            DB::connection('oracle_gds')->beginTransaction();

            foreach ($request->clases as $clase) {
                if(isset($clase['id_clase'])) {
                    clases_x_cusos::where('id_clase', $clase['id_clase'])
                        ->update([
                            'id_curso' => $request->id_curso,
                            'id_nivel' => $request->id_nivel,
                            'id_programa' => $request->id_programa,
                            'id_instructor' => $clase['instructor']['id_instructor'],
                            'id_sede' => $clase['sede']['id_sede'],
                            'id_horario' => $clase['horario']['id_horario'],
                            'id_temporalidad' => $clase['temporalidad']['id_temporalidad'],
                            'seccion' => mb_strtoupper($clase['seccion']),
                            'capacidad' => $clase['capacidad'],
                            'modalidad' => $clase['modalidad'],
                            'estatus' => $request->estatus,
                        ]);
                } else {
                    clases_x_cusos::create([
                        'id_curso' => $request->id_curso,
                        'id_nivel' => $request->id_nivel,
                        'id_programa' => $request->id_programa,
                        'id_instructor' => $clase['instructor']['id_instructor'],
                        'id_sede' => $clase['sede']['id_sede'],
                        'id_horario' => $clase['horario']['id_horario'],
                        'id_temporalidad' => $clase['temporalidad']['id_temporalidad'],
                        'seccion' => mb_strtoupper($clase['seccion']),
                        'capacidad' => $clase['capacidad'],
                        'modalidad' => $clase['modalidad'],
                        'estatus' => 'A',
                        'usuario' => auth()->user()->cui,
                        'fechau' => now(),
                    ]);
                }
            }

            DB::connection('oracle_gds')->commit();
            return response('Clases modificadas exitosamente');

        } catch (\Throwable $th) {
            DB::connection('oracle_gds')->rollBack();
            return response($th->getMessage(),422);
        }
    }

    public function destroy (clases_x_cusos $clase) {
        try {
            
        $clase->estatus = 'I';
        $clase->save();

        return response('Clase desactivada exitosamente.');

        } catch (\Throwable $th) {
            return response($th->getMessage(),422);
        }
    }

    public function getClase(Request $request) {

        $request->validate([
            'id_programa' => 'required',
            'id_nivel' => 'required',
            'field' => 'required',
        ]);

        try {
            
            $clases = clases_x_cusos::where('id_programa',$request->id_programa)
                ->where('id_nivel',$request->id_nivel)
                ->when(isset($request->id_curso),function($query) use ($request) {
                    $query->where('id_curso',$request->id_curso);
                })->where('estatus','A')
                ->with([
                    'curso',
                    'sede',
                ])
                ->get();
            return response($clases->unique($request->field));
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function getClases(Request $request) {

        $request->validate([
            'id_programa' => 'required',
            'id_nivel' => 'required',
        ]);

        try {
            
            $clases = clases_x_cusos::where('id_programa',$request->id_programa)
                ->where('id_nivel',$request->id_nivel)
                ->when(!is_null($request->id_curso),function ($query) use ($request) {
                    $query->where('id_curso',$request->id_curso);
                })->when(!is_null($request->id_sede),function ($query) use ($request) {
                    $query->where('id_sede',$request->id_sede);
                })->where('estatus','A')
                ->with([
                    'temporalidad',
                    'sede',
                    'instructor',
                    'horario',
                ])
                ->get();
            return response($clases->unique($request->field));
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
