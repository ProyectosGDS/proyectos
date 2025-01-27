<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class permisos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'PERMISOS';
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'app'
    ];
}
