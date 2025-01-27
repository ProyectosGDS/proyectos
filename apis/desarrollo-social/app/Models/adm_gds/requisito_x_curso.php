<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class requisito_x_curso extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'REQUISITO_X_CURSO';
    public $timestamps = false;

    protected $fillable = [
        'id_requisito',
        'id_curso',
        'obilgatorio',
        'usuario',
        'fechau',
    ];
}
