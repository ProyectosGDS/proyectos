<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class programas extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'PROGRAMAS';
    protected $primaryKey = 'id_programa';
    public $timestamps = false;

    protected $fillable = [
        'id_dependencia',
        'nombre',
        'estatus',
        'usuario',
        'fechau',
    ];

    public function dependencia() {
        return $this->belongsTo(dependencias::class,'id_dependencia','id_dependencia');
    }

    public function escuela() {
        return $this->hasOne(programas_x_escuela::class,'id_programa','id_programa');
    }

    public function niveles() {
        return $this->hasMany(niveles_x_programa::class,'id_programa','id_programa');
    }

    public function levels() {
        return $this->belongsToMany(niveles::class,'niveles_x_programa','id_programa','id_nivel');
    }
}
