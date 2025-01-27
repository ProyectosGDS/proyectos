<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class programas_x_escuela extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'PROGRAMAS_X_ESCUELA';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_programa',
        'id_escuela',
        'estatus',
        'usuario',
        'fechau',
    ];
}
