<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historial_personas extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'HISTORIAL_PERSONAS';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id_persona',
        'accion',
        'descripcion',
        'usuario',
        'fechau',
    ];

    // RELACIONES INVERSA

    public function persona() {
        return $this->belongsTo(personas::class,'id_persona','id_persona');
    }

    public function creado_por() {
        return $this->belongsTo(usuarios::class,'usuario','cui');
    }
}
