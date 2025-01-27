<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class tipo_eventos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'TIPO_EVENTOS';
    protected $primaryKey = 'id_tipo_evento';
    public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];
}
