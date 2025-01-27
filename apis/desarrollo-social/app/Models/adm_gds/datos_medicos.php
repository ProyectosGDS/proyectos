<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class datos_medicos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'DATOS_MEDICOS';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'id_tipo',
        'id_enfermedad',
        'medicamentos',
        'dosis',
    ];

    // RELACIONES INVERSAS

    public function persona() {
        return $this->belongsTo(personas::class,'id_persona','id_persona');
    }

    public function enfermedad() {
        return $this->belongsTo(enfermedades::class,'id_enfermedad','id_enfermedad');
    }

    public function tipo_sangre() {
        return $this->belongsTo(tipo_sangre::class,'id_tipo','id_tipo');
    }
}
