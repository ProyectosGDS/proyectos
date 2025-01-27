<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class temporalidad extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'TEMPORALIDAD';
    protected $primaryKey = 'id_temporalidad';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'usuario',
        'fechau',
    ];
}
