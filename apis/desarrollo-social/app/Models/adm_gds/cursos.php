<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class cursos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'CURSOS';
    protected $primaryKey = 'id_curso';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estatus',
        'usuario',
        'fechau',
    ];

    public function requisitos() {
        return $this->belongsToMany(requisitos::class,'requisito_x_curso','id_curso','id_requisito');
    }


    public function  clases() {
        return $this->hasMany(clases_x_cusos::class,'id_curso','id_curso');
    }

}
