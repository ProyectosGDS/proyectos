<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class cursos_x_persona extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'CURSOS_X_PERSONA';
    protected $primaryKey = 'id_correlativo';
    public $timestamps = false;

    protected $fillable = [
        'id_clase',
        'id_persona',
        'estatus',
        'usuario',
        'fechau',
    ];

    public function clase() {
        return $this->belongsTo(clases_x_cusos::class,'id_clase','id_clase');
    }

    public function persona() {
        return $this->belongsTo(personas::class,'id_persona','id_persona');
    }

    public function creado_por() {
        return $this->belongsTo(usuarios::class,'usuario','cui');
    }
}
