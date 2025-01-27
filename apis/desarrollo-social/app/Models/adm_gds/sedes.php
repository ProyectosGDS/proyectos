<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class sedes extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'SEDES';
    protected $primaryKey = 'id_sede';
    public $timestamps = false;

    protected $fillable = [
        'id_zona',
        'descripcion',
        'direccion',
        'distrito',
        'estatus',
        'usuario',
        'fechau',
    ];

    public function zona() {
        return $this->belongsTo(zonas::class,'id_zona','id_zona');
    }
}
