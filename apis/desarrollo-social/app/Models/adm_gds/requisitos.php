<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class requisitos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'REQUISITOS';
    protected $primaryKey = 'id_requisito';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estatus',
        'usuario',
        'fechau',
    ];
}
