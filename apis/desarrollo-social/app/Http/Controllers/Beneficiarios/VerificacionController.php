<?php

namespace App\Http\Controllers\Beneficiarios;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\cursos_x_persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificacionController extends Controller
{
    public function index (Request $request) {

        $id_dependencia = auth()->user()->id_dependencia;
        $id_programa = $request->input('id_programa') ?? null;
        $id_nivel = $request->input('id_nivel') ?? null;
        $id_curso = $request->input('id_curso') ?? null;

        try {
        
            $query = "
                SELECT
                    CP.ID_CORRELATIVO,
                    P.ID_PERSONA,
                    ConcatenarNombres(P.PRIMER_NOMBRE, P.SEGUNDO_NOMBRE, P.TERCER_NOMBRE, P.PRIMER_APELLIDO, P.SEGUNDO_APELLIDO, P.APELLIDO_CASADA) AS NOMBRE_COMPLETO,
                    PR.NOMBRE PROGRAMA,
                    N.DESCRIPCION NIVEL,
                    C.NOMBRE CURSO,
                    S.DESCRIPCION SEDE,
                    P.ESTATUS
                FROM CURSOS_X_PERSONA CP
                    INNER JOIN CLASES_X_CURSO CC
                        ON CP.ID_CLASE = CC.ID_CLASE
                    INNER JOIN PROGRAMAS PR
                        ON CC.ID_PROGRAMA = PR.ID_PROGRAMA
                    INNER JOIN NIVELES N
                        ON CC.ID_NIVEL = N.ID_NIVEL
                    INNER JOIN CURSOS C
                        ON CC.ID_CURSO = C.ID_CURSO
                    INNER JOIN SEDES S
                        ON CC.ID_SEDE = S.ID_SEDE
                    INNER JOIN PERSONAS P
                        ON CP.ID_PERSONA = P.ID_PERSONA
                WHERE PR.ID_DEPENDENCIA = ?
                AND CC.ID_PROGRAMA = ?
                AND CC.ID_NIVEL = ?
                AND CC.ID_CURSO = ?
                AND P.ESTATUS = 'P'
            ";

            $inscritos = DB::connection('oracle_gds')->select($query,[$id_dependencia,$id_programa,$id_nivel,$id_curso]);

            return response($inscritos);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
