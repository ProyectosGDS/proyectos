<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class parentescos extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'PARENTESCOS';
    protected $primaryKey = 'id_parentesco';
    public $timestamps = false;

    protected $fillable = [
        'descripcion'
    ];

    // RELACIONES

    public function responsables() {
        return $this->hasMany(responsables::class,'id_parentesco','id_parentesco');
    }
}
