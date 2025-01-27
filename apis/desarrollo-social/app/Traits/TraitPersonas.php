<?php

namespace App\Traits;

use App\Models\adm_gds\datos_academicos;
use App\Models\adm_gds\datos_medicos;
use App\Models\adm_gds\domicilios;
use App\Models\adm_gds\personas;
use App\Models\adm_gds\responsables;
use App\Rules\ValidateCui;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait TraitPersonas {

     // --------------- CREATE METHODS ---------------

    public $bagValidations = [];

    public function createPersonas(Request $request) {

        $validations = Validator::make($request->all(),[
            'cui' => ['required','numeric','digits:13',new ValidateCui,'unique:personas,cui'],
            'primer_nombre' => 'required|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'fecha_nacimiento' => 'required|date|date_format:Y-m-d|after:'.(date('Y') - 100).'-12-31',
            'email' => 'required|email',
            'celular' => 'required|numeric|digits:8'
        ]);

        if($validations->fails()){
            $this->bagValidations = array_merge($this->bagValidations, $validations->errors()->toArray());
            return;
        }

        $persona = personas::create([
            'cui'               => $request->cui,
            'primer_nombre'     => ucfirst(strtolower(trim($request->primer_nombre))),
            'segundo_nombre'    => ucfirst(strtolower(trim($request->segundo_nombre))) ?? null,
            'tercer_nombre'     => ucfirst(strtolower(trim($request->tercer_nombre))) ?? null,
            'primer_apellido'   => ucfirst(strtolower(trim($request->primer_apellido))),
            'segundo_apellido'  => ucfirst(strtolower(trim($request->segundo_apellido))) ?? null,
            'apellido_casada'   => ucwords(strtolower(trim($request->apellido_casada))) ?? null,
            'fecha_nacimiento'  => $request->fecha_nacimiento,
            'sexo'              => $request->sexo,
            'lugar_nacimiento'  => $request->lugar_nacimiento ?? null,
            'nit'               => $request->nit ? (strpos($request->nit,"-") ? str_replace("-","",$request->nit) : $request->nit )  : null,
            'pasaporte'         => $request->pasaporte ?? null,
            'id_etnia'   => $request->id_etnia ?? null,
            'id_estado'   => $request->id_estado ?? null,
            'interlocutor'   => $request->interlocutor ?? null,
            'telefono' => $request->telefono ?? null,
            'celular' => trim($request->celular),
            'email' => $request->email,
            'facebook' => $request->facebook ?? null,
            'instagram' => $request->instagram ?? null,
            'tiktok' => $request->tiktok ?? null,
            'estatus'         => 'A',
            'usuario' => auth()->user()->cui,
            'fechau' => now(),
        ]);

        return $persona;

    }

    public function createDomicilios (Request $request, int $id_persona) {

        $domicilios = domicilios::create([
            'id_persona' => $id_persona,
            'numero_via' => $request->domicilios['numero_via'] ?? null,
            'id_via' => $request->domicilios['id_via'] ?? null,
            'nomenclatura' => $request->domicilios['nomenclatura'] ?? null,
            'complemento' => $request->domicilios['complemento'] ?? null,
            'zona' => $request->domicilios['zona'] ?? null,
            'id_grupo_zona' => $request->domicilios['id_grupo_zona'] ?? null,
        ]);

        return $domicilios;
    }

    public function createDatosMedicos(Request $request, int $id_persona) {

        $datosMedicos = datos_medicos::create([
            'id_persona' => $id_persona,
            'id_tipo' => $request->datos_medicos['id_tipo'] ?? null,
            'id_enfermedad' => $request->datos_medicos['id_enfermedad'] ?? null,
            'medicamentos' => $request->datos_medicos['medicamentos'] ?? null ,
            'dosis' => $request->datos_medicos['dosis'] ?? null,
        ]);
        
        return $datosMedicos;
        
    }

    public function createResponsables(Request $request, int $id_persona) {

        $validations = Validator::make($request->all(),[
            'responsables.id_parentesco' => 'required|',
            'responsables.cui' => ['required','numeric','digits:13',new ValidateCui],
            'responsables.nombre' => 'required|string|max:150',
            'responsables.celular' => 'required|numeric|digits:8',
        ]);

        if($validations->fails()){
            $this->bagValidations = array_merge($this->bagValidations, $validations->errors()->toArray());
            return;
        }

        $responsables = responsables::create([
            'id_persona' => $id_persona,
            'id_parentesco' => $request->responsables['id_parentesco'],
            'cui' => $request->responsables['cui'],
            'categoria' => 'R',
            'nombre' => strtoupper(trim($request->responsables['nombre'])),
            'email' => $request->has('responsables.email') ? strtolower($request->responsables['email']) : null,
            'celular' => $request->responsables['celular'],
            'sexo' => $request->responsables['sexo'] ?? null,
            'domicilio' => $request->responsables['domicilio'],
            'zona' => $request->responsables['zona'] ?? null,
        ]);

        return $responsables;

    }

    public function createEmergencia(Request $request, int $id_persona) {

        $validations = Validator::make($request->all(),[
            'emergencia.id_parentesco' => 'required|',
            'emergencia.nombre' => 'required|string|max:150',
            'emergencia.celular' => 'required|numeric|digits:8',
        ]);

        if($validations->fails()){
            $this->bagValidations = array_merge($this->bagValidations, $validations->errors()->toArray());
            return;
        }

        $emergencia = responsables::create([
            'id_persona' => $id_persona,
            'id_parentesco' => $request->emergencia['id_parentesco'],
            'cui' => $request->emergencia['cui'],
            'categoria' => 'E',
            'nombre' => strtoupper(trim($request->emergencia['nombre'])),
            'email' => $request->has('emergencia.email') ? strtolower($request->emergencia['email']) : null,
            'celular' => $request->emergencia['celular'],
            'sexo' => $request->emergencia['sexo'] ?? null,
            'domicilio' => $request->emergencia['domicilio'],
            'zona' => $request->emergencia['zona'] ?? null,
        ]);

        return $emergencia;

    }

    public function createDatosAcademicos(Request $request, int $id_persona) {
        $validations = Validator($request->all(),[
            'datos_academicos.id_escolaridad' => 'required',
            'datos_academicos.tipo_establecimiento' => 'required',
        ]);

        if($validations->fails()){
            $this->bagValidations = array_merge($this->bagValidations, $validations->errors()->toArray());
            return;
        }

        $datosAcademicos = datos_academicos::create([
            'id_persona' => $id_persona,
            'id_escolaridad' => $request->datos_academicos['id_escolaridad'],
            'tipo_establecimiento' => $request->datos_academicos['tipo_establecimiento'],
            'establecimiento' => $request->has('datos_academicos.establecimiento') ? strtoupper($request->datos_academicos['establecimiento']) : null,
            'titulo' => $request->has('datos_academicos.titulo') ? strtoupper($request->datos_academicos['titulo']) : null,
        ]);

        return $datosAcademicos;
    
    }

    // --------------- UPDATE METHODS ---------------

    public function updatePersonas(Request $request, personas $persona) {

        $validations = Validator::make($request->all(),[
            'cui' => ['required','numeric','digits:13', new ValidateCui, Rule::unique('personas','cui')->ignore($persona->id_persona,'id_persona')],
            'primer_nombre' => 'required|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'fecha_nacimiento' => 'required|date|date_format:Y-m-d|after:'.(date('Y') - 100).'-12-31',
            'email' => 'required|email',
            'celular' => 'required|numeric|digits:8'
        ]);

        if($validations->fails()){
            $this->bagValidations = array_merge($this->bagValidations, $validations->errors()->toArray());
            return;
        }

        $persona = $persona->update([
            'cui'               => $request->cui,
            'primer_nombre'     => ucfirst(strtolower(trim($request->primer_nombre))),
            'segundo_nombre'    => ucfirst(strtolower(trim($request->segundo_nombre))) ?? null,
            'tercer_nombre'     => ucfirst(strtolower(trim($request->tercer_nombre))) ?? null,
            'primer_apellido'   => ucfirst(strtolower(trim($request->primer_apellido))),
            'segundo_apellido'  => ucfirst(strtolower(trim($request->segundo_apellido))) ?? null,
            'apellido_casada'   => ucwords(strtolower(trim($request->apellido_casada))) ?? null,
            'fecha_nacimiento'  => $request->fecha_nacimiento,
            'sexo'              => $request->sexo,
            'lugar_nacimiento'  => $request->lugar_nacimiento ?? null,
            'nit'               => $request->nit ? (strpos($request->nit,"-") ? str_replace("-","",$request->nit) : $request->nit )  : null,
            'pasaporte'         => $request->pasaporte ?? null,
            'id_etnia'          => $request->id_etnia ?? null,
            'id_estado'         => $request->id_estado ?? null,
            'interlocutor'      => $request->interlocutor ?? null,
            'telefono'          => $request->telefono ?? null,
            'celular'           => trim($request->celular),
            'email'             => $request->email,
            'facebook'          => $request->facebook ?? null,
            'instagram'         => $request->instagram ?? null,
            'tiktok'            => $request->tiktok ?? null,
            'estatus'           => $request->estatus,
        ]);

        return $persona;

    }

    public function updateDomicilios (Request $request, personas $persona) {

        $domicilios = $persona->domicilios()->update([
            'numero_via' => $request->domicilios['numero_via'] ?? null,
            'id_via' => $request->domicilios['id_via'] ?? null,
            'nomenclatura' => $request->domicilios['nomenclatura'] ?? null,
            'complemento' => $request->domicilios['complemento'] ?? null,
            'zona' => $request->domicilios['zona'] ?? null,
            'id_grupo_zona' => $request->domicilios['id_grupo_zona'] ?? null,
        ]);

        return $domicilios;
    }

    public function updateDatosMedicos(Request $request, personas $persona) {

        $datosMedicos = $persona->datos_medicos()->update([
            'id_tipo' => $request->datos_medicos['id_tipo'] ?? null,
            'id_enfermedad' => $request->datos_medicos['id_enfermedad'] ?? null,
            'medicamentos' => $request->datos_medicos['medicamentos'] ?? null ,
            'dosis' => $request->datos_medicos['dosis'] ?? null,
        ]);

        return $datosMedicos;

    }

    public function updateResponsables(Request $request, personas $persona) {

        $validations = Validator::make($request->all(),[
            'responsables.id_parentesco' => 'required|',
            'responsables.cui' => ['required','numeric','digits:13',new ValidateCui],
            'responsables.nombre' => 'required|string|max:150',
            'responsables.celular' => 'required|numeric|digits:8',
            'responsables.domicilio' => 'required|string|max:150',
        ]);

        if($validations->fails()){
            $this->bagValidations = array_merge($this->bagValidations, $validations->errors()->toArray());
            return;
        }

        $responsables = $persona->responsables()->update([
            'id_parentesco' => $request->responsables['id_parentesco'],
            'cui' => $request->responsables['cui'],
            'nombre' => strtoupper(trim($request->responsables['nombre'])),
            'email' => $request->responsables['email'] ? strtolower($request->responsables['email']) : null,
            'celular' => $request->responsables['celular'],
            'sexo' => $request->responsables['sexo'] ?? null,
            'domicilio' => $request->responsables['domicilio'],
            'zona' => $request->responsables['zona'] ?? null,
        ]);

        return $responsables;

    }

    public function updateEmergencia(Request $request, personas $persona) {

        $validations = Validator::make($request->all(),[
            'emergencia.id_parentesco' => 'required|',
            'emergencia.nombre' => 'required|string|max:150',
            'emergencia.celular' => 'required|numeric|digits:8',
        ]);

        if($validations->fails()){
            $this->bagValidations = array_merge($this->bagValidations, $validations->errors()->toArray());
            return;
        }

        $emergencia = $persona->emergencia()->update([
            'id_parentesco' => $request->emergencia['id_parentesco'],
            'cui' => $request->emergencia['cui'],
            'nombre' => strtoupper(trim($request->emergencia['nombre'])),
            'email' => $request->emergencia['email'] ? strtolower($request->emergencia['email']) : null,
            'celular' => $request->emergencia['celular'],
            'sexo' => $request->emergencia['sexo'] ?? null,
            'domicilio' => $request->emergencia['domicilio'],
            'zona' => $request->emergencia['zona'] ?? null,
        ]);

        return $emergencia;

    }

    public function updateDatosAcademicos(Request $request, personas $persona) {

        $validations = Validator($request->all(),[
            'datos_academicos.id_escolaridad' => 'required',
            'datos_academicos.tipo_establecimiento' => 'required',
        ]);

        if($validations->fails()){
            $this->bagValidations = array_merge($this->bagValidations, $validations->errors()->toArray());
            return;
        }

        $datosAcademicos = $persona->datos_academicos()->update([
            'id_escolaridad' => $request->datos_academicos['id_escolaridad'],
            'tipo_establecimiento' => $request->datos_academicos['tipo_establecimiento'],
            'establecimiento' => $request->datos_academicos['establecimiento'] ? strtoupper($request->datos_academicos['establecimiento']) : null,
            'titulo' => $request->datos_academicos['titulo'] ? strtoupper($request->datos_academicos['titulo']) : null,
        ]);

        return $datosAcademicos;
    
    }
}