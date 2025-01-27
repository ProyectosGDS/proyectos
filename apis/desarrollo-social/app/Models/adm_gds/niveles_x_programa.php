<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class niveles_x_programa extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'NIVELES_X_PROGRAMA';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_programa',
        'id_nivel',
        'estatus',
        'usuario',
        'fechau',
    ];
}
