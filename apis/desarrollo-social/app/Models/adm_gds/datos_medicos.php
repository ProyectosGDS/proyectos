<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class datos_medicos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'DATOS_MEDICOS';
    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'id_tipo',
        'medicamentos',
        'dosis',
    ];

    // RELACIONES INVERSAS

    public function persona() {
        return $this->belongsTo(personas::class,'id_persona','id_persona');
    }

    public function enfermedades_x_persona() {
        return $this->belongsToMany(enfermedades::class,'enfermedades_x_persona','datos_medicos_id','enfermedad_id');
    }

    public function tipo_sangre() {
        return $this->belongsTo(tipo_sangre::class,'id_tipo','id_tipo');
    }
}
