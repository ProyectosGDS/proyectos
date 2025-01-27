<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class eventos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'EVENTOS';
    protected $primaryKey = 'id_evento';
    public $timestamps = false;
    protected $appends = [
        'fecha',
        'hora'
    ];

    protected $casts = [
        'fecha_ini' => 'datetime:Y-m-d',
        'fecha_fin' => 'datetime:Y-m-d',
    ];

    protected $fillable = [
        'id_tipo_evento',
        'titulo',
        'descripcion',
        'ubicacion',
        'fecha_ini',
        'fecha_fin',
        'hora_ini',
        'hora_fin',
        'responsable',
        'duracion',
        'id_estatus',
        'usuario',
        'fechau',
        'id_dependencia',

    ];

    public function estado_evento() {
        return $this->belongsTo(estatus_eventos::class,'id_estatus','id_estatus');
    }

    public function tipo_evento() {
        return $this->belongsTo(tipo_eventos::class,'id_tipo_evento','id_tipo_evento');
    }

    public function dependencia() {
        return $this->belongsTo(dependencias::class,'id_dependencia','id_dependencia');
    }

    public function creado_por() {
        return $this->belongsTo(usuarios::class,'usuario','cui');
    }

    public function getFechaAttribute() {
        return date('d-m-Y',strtotime($this->fecha_ini)).' A '.date('d-m-Y',strtotime($this->fecha_fin));
    }

    public function getHoraAttribute() {
        return $this->hora_ini.' A '.$this->hora_fin;
    }

}
