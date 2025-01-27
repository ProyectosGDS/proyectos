<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class escolaridad extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'ESCOLARIDAD';
    protected $primaryKey = 'id_escolaridad';
    public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    // RELACIONES

    public function datos_academicos() {
        return $this->hasMany(datos_academicos::class,'id_escolaridad','id_escolaridad');
    }
}
