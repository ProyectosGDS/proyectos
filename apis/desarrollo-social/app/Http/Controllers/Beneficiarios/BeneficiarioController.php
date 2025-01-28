<?php

namespace App\Http\Controllers\Beneficiarios;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Cursos\ProgramasController;
use App\Http\Resources\BeneficiarioUnicoResource;
use App\Models\adm_gds\enfermedades;
use App\Models\adm_gds\escolaridad;
use App\Models\adm_gds\estado_civil;
use App\Models\adm_gds\etnias;
use App\Models\adm_gds\grupo_habitacional;
use App\Models\adm_gds\historial_personas;
use App\Models\adm_gds\parentescos;
use App\Models\adm_gds\personas;
use App\Models\adm_gds\tipo_sangre;
use App\Models\adm_gds\vias;
use App\Models\adm_gds\zonas;
use App\Models\Muni\TbBeneficiarioUnico;
use App\Rules\ValidateCui;
use App\Traits\TraitPersonas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeneficiarioController extends Controller
{
    use TraitPersonas;

    public function index() {
        try {

            $query = "
                SELECT
                ID_PERSONA,
                CUI,
                ConcatenarNombres(PRIMER_NOMBRE, SEGUNDO_NOMBRE, TERCER_NOMBRE, PRIMER_APELLIDO, SEGUNDO_APELLIDO, APELLIDO_CASADA) AS NOMBRE_COMPLETO,
                FLOOR(MONTHS_BETWEEN(SYSDATE, FECHA_NACIMIENTO) / 12) AS EDAD,
                SEXO,
                CELULAR,
                EMAIL,
                ESTATUS
            FROM PERSONAS
            ORDER BY ID_PERSONA DESC
            ";

            $beneficiarios = DB::connection('oracle_gds')->select($query);

            return response($beneficiarios);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function getBeneficiarios(Request $request) {

        $search = $request->input('search') ?? '';
        $column = $request->input('column') ?? 'id_persona';
        $order = $request->input('order') ?? 'desc';
        $per_page = $request->input('per_page') ?? 10;

        try {
            $personas = personas::where(function($query) use($search) {
                $query->where('cui','LIKE','%'.$search.'%')
                    ->orWhereRaw(
                        "LOWER(ConcatenarNombres(PRIMER_NOMBRE, SEGUNDO_NOMBRE, TERCER_NOMBRE, PRIMER_APELLIDO, SEGUNDO_APELLIDO, APELLIDO_CASADA)) LIKE ?",
                        ["%" . strtolower($search) . "%"]
                    )
                    ->orWhere('celular','LIKE','%'. $search .'%')
                    ->orWhere('email','LIKE','%'. $search .'%');
            })
            ->orderBy(($column === 'nombre_completo') ? 'primer_nombre' : $column,$order)
            ->paginate($per_page);
            return response($personas);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function show(personas $beneficiario) {
        try {
            $beneficiario = $beneficiario->load([
                'domicilios.grupo_x_zona',
                'responsables',
                'emergencia',
                'datos_academicos',
                'datos_medicos',
            ]);
            return response($beneficiario);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store(Request $request) {

        DB::connection('oracle_gds')->beginTransaction();
        
        try {
            
            $persona = $this->createPersonas($request);

            if($persona) {

                if (
                    $request->has('domicilios.numero_via') ||
                    $request->has('domicilios.id_via') ||
                    $request->has('domicilios.nomenclatura') ||
                    $request->has('domicilios.complemento') ||
                    $request->has('domicilios.zona') ||
                    $request->has('domicilios.id_grupo_zona')
                ){

                    $this->createDomicilios($request,$persona->id_persona);
                }

                if (
                    $request->has('datos_academicos.id_escolaridad') ||
                    $request->has('datos_academicos.tipo_establecimiento') ||
                    $request->has('datos_academicos.establecimiento') ||
                    $request->has('datos_academicos.titulo')
                ) {

                    $this->createDatosAcademicos($request,$persona->id_persona);
                }


                if(
                    $request->has('datos_medicos.id_tipo') ||
                    $request->has('datos_medicos.id_enfermedad') ||
                    $request->has('datos_medicos.medicamentos') ||
                    $request->has('datos_medicos.dosis')
                ){
                    $this->createDatosMedicos($request,$persona->id_persona);
                }


                if(isset($request->edad) && (intval($request->edad) < 18)) {
                    if(
                        $request->has('responsables.id_parentesco') ||
                        $request->has('responsables.cui') ||
                        $request->has('responsables.nombre') ||
                        $request->has('responsables.email') ||
                        $request->has('responsables.celular') ||
                        $request->has('responsables.domicilio') ||
                        $request->has('responsables.zona')
                    ) {
                        $this->createResponsables($request,$persona->id_persona);
                    }
                }



                if(
                    $request->has('emergencia.id_parentesco') ||
                    $request->has('emergencia.cui') ||
                    $request->has('emergencia.nombre') ||
                    $request->has('emergencia.email') ||
                    $request->has('emergencia.celular') ||
                    $request->has('emergencia.domicilio') ||
                    $request->has('emergencia.zona')
                ) {
                    $this->createEmergencia($request,$persona->id_persona);
                }

                historial_personas::create([
                    'id_persona' => $persona->id_persona,
                    'accion' => 'CREACION BENEFICIARIO',
                    'descripcion' => 'SE CREO BENEFICIARIO',
                    'usuario' => auth()->user()->cui,
                    'fechau' => now()
                ]);

            }



            if(!empty($this->bagValidations)){
                DB::connection('oracle_gds')->rollBack();
                return response(['errors' => $this->bagValidations],422);
            }


            

            DB::connection('oracle_gds')->commit();
            
            return response('Se ha almacenado los datos correctamente');

        } catch (\Throwable $th) {

            DB::connection('oracle_gds')->rollBack();
            return response($th->getMessage(),422);

        }
    }

    public function update(Request $request, personas $beneficiario) {

        DB::connection('oracle_gds')->beginTransaction();
        
        try {
            
            $this->updatePersonas($request, $beneficiario);

            if (!is_null($beneficiario->domicilios)) {
                $this->updateDomicilios($request, $beneficiario);
            }else {
                if (
                    $request->has('domicilios.numero_via') ||
                    $request->has('domicilios.id_via') ||
                    $request->has('domicilios.nomenclatura') ||
                    $request->has('domicilios.complemento') ||
                    $request->has('domicilios.zona') ||
                    $request->has('domicilios.id_grupo_zona')
                ){
                    $this->createDomicilios($request,$beneficiario->id_persona);
                }
            }

            if(!is_null($beneficiario->datos_academicos)) {
                $this->updateDatosAcademicos($request,$beneficiario);
            }else {
                if (
                    $request->has('datos_academicos.id_persona') ||
                    $request->has('datos_academicos.id_escolaridad') ||
                    $request->has('datos_academicos.tipo_establecimiento') ||
                    $request->has('datos_academicos.establecimiento') ||
                    $request->has('datos_academicos.titulo')
                ) {
                    $this->createDatosAcademicos($request, $beneficiario->id_persona);
                }
            }


            if(!is_null($beneficiario->datos_medicos)) {
                $this->updateDatosMedicos($request, $beneficiario);
            } else {
                if(
                    $request->has('datos_medicos.id_tipo') ||
                    $request->has('datos_medicos.id_enfermedad') ||
                    $request->has('datos_medicos.medicamentos') ||
                    $request->has('datos_medicos.dosis')
                ){
                    $this->createDatosMedicos($request, $beneficiario->id_persona);
                }
            }

            if(isset($request->edad) && (intval($request->edad) < 18)) {

                if(!is_null($beneficiario->responsables)) {
                    $this->updateResponsables($request, $beneficiario);
                } else {
                    if(
                        $request->has('responsables.id_parentesco') ||
                        $request->has('responsables.cui') ||
                        $request->has('responsables.categoria') ||
                        $request->has('responsables.nombre') ||
                        $request->has('responsables.email') ||
                        $request->has('responsables.celular') ||
                        $request->has('responsables.domicilio') ||
                        $request->has('responsables.zona')
                    ) {
                        $this->createResponsables($request, $beneficiario->id_persona);
                    }
                }
            }

            if(!is_null($beneficiario->emergencia)) {
                $this->updateEmergencia($request, $beneficiario);
            } else {
                if (
                    $request->has('emergencia.id_parentesco') ||
                    $request->has('emergencia.cui') ||
                    $request->has('emergencia.categoria') ||
                    $request->has('emergencia.nombre') ||
                    $request->has('emergencia.email') ||
                    $request->has('emergencia.celular') ||
                    $request->has('emergencia.domicilio') ||
                    $request->has('emergencia.zona')
                ) {
                    $this->createEmergencia($request, $beneficiario->id_persona);
                }
            }


            historial_personas::create([
                'id_persona' => $beneficiario->id_persona,
                'accion' => 'ACTUALIZACION DE DATOS',
                'descripcion' => 'SE MODIFICO INFORMACION DEL BENEFICIARIO',
                'usuario' => auth()->user()->cui,
                'fechau' => now()
            ]);
            



            if(!empty($this->bagValidations)){
                DB::connection('oracle_gds')->rollBack();
                return response(['errors' => $this->bagValidations],422);
            }

            DB::connection('oracle_gds')->commit();
            
            return response('Se ha almacenado los datos correctamente');

        } catch (\Throwable $th) {

            DB::connection('oracle_gds')->rollBack();
            return response($th->getMessage(),422);

        }
    }
    
    public function destroy(personas $beneficiario) {
        try {
            
            $beneficiario->estatus = 'I';
            $beneficiario->save();

            historial_personas::create([
                'id_persona' => $beneficiario->id_persona,
                'accion' => 'DESHABILITACION BENEFICIARIO',
                'descripcion' => 'SE DESHABILITO BENEFICIARIO',
                'usuario' => auth()->user()->cui,
                'fechau' => now()
            ]);

            return response('Se ha desactivado exitosamente');

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function catalogos() {
        try {

            $programas = new ProgramasController();

            $catalogos = [
                'etnias' => etnias::all(),
                'estado_civil' => estado_civil::all(),
                'vias' => vias::all(),
                'zonas' => zonas::all(),
                'grupo_habitacional' => grupo_habitacional::all(),
                'tipo_sangre' => tipo_sangre::all(),
                'enfermedades' => enfermedades::all(),
                'escolaridades' => escolaridad::all(),
                'parentescos' => parentescos::all(),
                'programas' => $programas->index()->original,
            ];
            
            return response($catalogos);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function bitacoras(personas $beneficiario) {
        try {
            $bitacoras = [
                'asignaciones' => $beneficiario->cursos_x_persona()->with([
                    'creado_por',
                    'clase.curso',
                    'clase.sede',
                ])->latest('fechau')->get(),
                'observaciones' => $beneficiario->historial_persona()->with(['creado_por'])->where('accion','OBSERVACION BENEFICIARIO')->latest('fechau')->get(),
                'acciones' => $beneficiario->historial_persona()->with(['creado_por'])->whereNotIn('accion',['OBSERVACION BENEFICIARIO'])->latest('fechau')->get(),
            ];
            return response($bitacoras);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function consultaBackUp(Request $request) {
        $request->validate([
            'cui' => ['required','numeric','digits:13',new ValidateCui ],
        ]);

        try {
            
            $beneficiarioUnico = TbBeneficiarioUnico::where('cui',$request->cui)->firstOrFail();
        
            return response(BeneficiarioUnicoResource::make($beneficiarioUnico));

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function asignarCurso(Request $request, personas $persona) {
        $request->validate([
            'id_clase' => 'required',
        ]);

        try {

            if($request->id_sede) {
                
            }

            $persona = $persona->cursos_x_persona()->create([
                'id_clase' => $request->id_clase,
                'estatus' => 'A',
                'usuario' => auth()->user()->cui,
                'fechau' => now(),
            ]);

            return response('Asignación realizada exitosamente');
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
