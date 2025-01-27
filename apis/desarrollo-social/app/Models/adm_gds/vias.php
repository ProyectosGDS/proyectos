<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class vias extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'VIAS';
    protected $primaryKey = 'id_via';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
    ];

    // RELACIONES

    public function domicilios() {
        return $this->hasMany(domicilios::class,'id_via','id_via');
    }
}
