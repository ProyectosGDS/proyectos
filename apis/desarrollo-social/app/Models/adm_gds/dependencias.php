<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class dependencias extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'DEPENDENCIAS';
    protected $primaryKey = 'id_dependencia';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'usuario',
        'fechau',
    ];

    public function programas() {
        return $this->hasMany(programas::class,'id_programa','id_dependencia');
    }
}
