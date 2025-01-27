<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class enfermedades extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'ENFERMEDADES';
    protected $primaryKey = 'id_enfermedad';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
    ];

    public function datos_medicos() {
        return $this->hasMany(datos_medicos::class,'id_enfermedad','id_enfermedad');
    }
}
