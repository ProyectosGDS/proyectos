<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class responsables extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'RESPONSABLES';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'id_parentesco',
        'cui',
        'categoria',
        'nombre',
        'email',
        'celular',
        'sexo',
        'domicilio',
        'zona',
    ];

    // RELACIONES 

    public function persona() {
        return $this->belongsTo(personas::class,'id_persona','id_persona');
    }

    public function parentescos() {
        return $this->belongsTo(parentescos::class,'id_parentesco','id_parentesco');
    }
}
