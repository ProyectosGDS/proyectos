<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class estado_civil extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'ESTADO_CIVIL';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
    ];

    // RELACIONES

    public function personas() {
        return $this->hasMany(personas::class,'id_persona','id_persona');
    }
}
