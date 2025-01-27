<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'ROLES';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function permisos() {
        return $this->belongsToMany(permisos::class,'permisos_roles','id_rol','id_permiso');
    }

}
