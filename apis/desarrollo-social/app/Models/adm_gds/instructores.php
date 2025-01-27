<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class instructores extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'INSTRUCTORES';
    protected $primaryKey = 'id_instructor';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estatus',
        'usuario',
        'fechau',
    ];
}
