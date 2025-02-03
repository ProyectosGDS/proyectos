<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class municipios extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'MUNICIPIOS';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'departamento_id'
    ];

    // RELACIONES INVERSA

    public function departamento() {
        return $this->belongsTo(departamentos::class,'departamento_id');
    }


}
