<?php

namespace App\Models\adm_gds;


use Illuminate\Database\Eloquent\Model;

class zonas extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'ZONAS';
    protected $primaryKey = 'id_zona';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
    ];

    // RELACIONES

    public function grupos_x_zona() {
        return $this->hasMany(grupos_x_zona::class,'id_zona','id_zona');
    }
}
