<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class clases_x_cusos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'CLASES_X_CURSO';
    protected $primaryKey = 'id_clase';
    public $timestamps = false;

    protected $fillable = [
        'id_curso',
        'id_programa',
        'id_nivel',
        'id_instructor',
        'id_sede',
        'id_temporalidad',
        'id_horario',
        'seccion',
        'capacidad',
        'modalidad',
        'estatus',
        'usuario',
        'fechau',

    ];

    // RELACIONES

    public function personas_asignadas() {
        return $this->hasMany(cursos_x_persona::class,'id_clase','id_clase')->whereIn('estatus',['A','P']);
    }

    // RELACIONES INVERSAS

    public function curso() {
        return $this->belongsTo(cursos::class,'id_curso','id_curso');
    }
    
    public function programa() {
        return $this->belongsTo(programas::class,'id_programa','id_programa');
    }

    public function nivel() {
        return $this->belongsTo(niveles::class,'id_nivel','id_nivel');
    }

    public function instructor() {
        return $this->belongsTo(instructores::class,'id_instructor','id_instructor');
    }

    public function sede() {
        return $this->belongsTo(sedes::class,'id_sede','id_sede');
    }

    public function temporalidad() {
        return $this->belongsTo(temporalidad::class,'id_temporalidad','id_temporalidad');
    }

    public function horario() {
        return $this->belongsTo(horarios::class,'id_horario','id_horario');
    }

}
