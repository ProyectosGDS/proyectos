<?php

namespace App\Models\adm_gds;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class personas extends Model
{

    protected $connection = 'oracle_gds';
    protected $table = 'PERSONAS';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $appends = ['nombre_completo','edad'];
    protected $casts = [
        'fecha_nacimiento' => 'datetime:Y-m-d',
    ];

    protected $fillable = [
        'id_etnia',
        'id_estado',
        'primer_nombre',
        'segundo_nombre',
        'tercer_nombre',
        'primer_apellido',
        'segundo_apellido',
        'apellido_casada',
        'cui',
        'lugar_nacimiento',
        'fecha_nacimiento',
        'sexo',
        'nit',
        'pasaporte',
        'celular',
        'telefono',
        'email',
        'facebook',
        'tiktok',
        'instagram',
        'estatus',
        'usuario',
        'fechau',

    ];

    // RELACIONES

    public function historial_persona() {
        return $this->hasMany(historial_personas::class,'id_persona','id_persona');
    }
    public function domicilios() {
        return $this->hasOne(domicilios::class,'id_persona','id_persona');
    }
    public function responsables() {
        return $this->hasOne(responsables::class,'id_persona','id_persona')->where('categoria','R');
    }

    public function emergencia() {
        return $this->hasOne(responsables::class,'id_persona','id_persona')->where('categoria','E');
    }

    public function datos_academicos() {
        return $this->hasOne(datos_academicos::class,'id_persona','id_persona');
    }
    public function datos_medicos() {
        return $this->hasOne(datos_medicos::class,'id_persona','id_persona');
    }

    public function cursos_x_persona() {
        return $this->hasMany(cursos_x_persona::class,'id_persona','id_persona');
    }

    // RELACIONES INVERSAS

    public function estado_civil() {
        return $this->belongsTo(estado_civil::class,'id_estado','id_estado');
    }

    public function etnia() {
        return $this->belongsTo(etnias::class,'id_etnia','id_etnia');
    }

    public function creado_por() {
        return $this->belongsTo(usuarios::class,'usuario','cui');
    }
    

    // MUTADORES

    public function getNombreCompletoAttribute() {
        $nombres = [
            $this->primer_nombre,
            $this->segundo_nombre,
            $this->tercer_nombre,
            $this->primer_apellido,
            $this->segundo_apellido,
            $this->apellido_casada,
        ];
    
        $nombres = array_filter($nombres, function ($nombre) {
            return !is_null($nombre) && $nombre !== '';
        });
        
        return implode(' ', $nombres);
    }

    public function getEdadAttribute() {
        $fechaNacimiento = new DateTime($this->fecha_nacimiento);
        $fechaActual = new DateTime();
        $edad = $fechaNacimiento->diff($fechaActual);
        return $edad->y;
    }

}
