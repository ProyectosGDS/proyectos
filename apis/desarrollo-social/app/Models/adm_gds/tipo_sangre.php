<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class tipo_sangre extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'TIPOS_SANGRE';
    protected $primaryKey = 'id_tipo';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
    ];
    

    // RELACIONES
    
    public function datos_medicos() {
        return $this->hasMany(datos_medicos::class,'id_tipo','id_tipo');
    }
}
