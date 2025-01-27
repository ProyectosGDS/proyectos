<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class grupos_x_zona extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'GRUPOS_X_ZONA';
    protected $primaryKey = 'id_grupo_zona';
    public $timestamps = false;

    protected $fillable = [
        'id_zona',
        'id_grupo',
        'descripcion',
    ];

    // RELACIONES

    public function domicilios() {
        return $this->hasMany(domicilios::class,'id_grupo_zona','id_grupo_zona');
    }

    // RELACION INVERSA
    public function grupo_habitacional() {
        return $this->belongsTo(grupo_habitacional::class,'id_grupo','id_grupo');
    }
}
