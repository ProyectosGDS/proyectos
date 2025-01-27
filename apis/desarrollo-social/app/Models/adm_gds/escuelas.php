<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class escuelas extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'ESCUELAS';
    protected $primaryKey = 'id_escuela';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'usuario',
        'fechau',
    ];
}
