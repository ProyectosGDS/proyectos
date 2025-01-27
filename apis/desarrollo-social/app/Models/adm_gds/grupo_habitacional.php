<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class grupo_habitacional extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'GRUPO_HABITACIONAL';
    protected $primaryKey = 'id_grupo';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
    ];

    // RELACIONES

    public function grupo_x_zona() {
        return $this->hasMany(grupos_x_zona::class,'id_grupo','id_grupo');
    }
}
