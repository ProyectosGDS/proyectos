<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class domicilios extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'DOMICILIOS';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'numero_via',
        'id_via',
        'nomenclatura',
        'complemento',
        'zona',
        'id_grupo_zona',
    ];

    // RELACIONES INVERSAS

    public function persona() {
        return $this->belongsTo(personas::class,'id_persona','id_persona');
    }
    public function via() {
        return $this->belongsTo(vias::class,'id_via','id_via');
    }
    public function grupo_x_zona() {
        return $this->belongsTo(grupos_x_zona::class,'id_grupo_zona','id_grupo_zona');
    }


}
