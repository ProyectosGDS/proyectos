<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class etnias extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'ETNIAS';
    protected $primaryKey = 'id_etnia';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
    ];

    public function personas() {
        return $this->hasMany(personas::class,'id_persona','id_persona');
    }
}
