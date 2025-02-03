<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class departamentos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'DEPARTAMENTOS';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    // RELACIONES

    public function municipios() {
        return $this->hasMany(municipios::class,'departamento_id');
    }
}
