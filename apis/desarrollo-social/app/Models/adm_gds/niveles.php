<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class niveles extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'NIVELES';
    protected $primaryKey = 'id_nivel';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'estatus',
        'usuario',
        'fechau',
    ];

    public function programas() {
        return $this->belongsToMany(programas::class,'niveles_x_programa','id_nivel','programa_id');
    }
}
