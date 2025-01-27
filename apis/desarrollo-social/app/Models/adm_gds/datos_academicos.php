<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class datos_academicos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'DATOS_ACADEMICOS';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'id_escolaridad',
        'tipo_establecimiento',
        'establecimiento',
        'titulo',
    ];

    // RELACIONES INVESAS
    public function  persona() {
        return $this->belongsTo(personas::class,'id_persona','id_persona');
    }

    public function  escolaridad() {
        return $this->belongsTo(escolaridad::class,'id_escolaridad','id_escolaridad');
    }
}
