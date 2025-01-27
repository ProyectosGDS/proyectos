<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class estatus_eventos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'ESTATUS_EVENTOS';
    protected $primaryKey = 'id_estatus';
    public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];
}
