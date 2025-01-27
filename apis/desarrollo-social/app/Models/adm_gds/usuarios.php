<?php

namespace App\Models\adm_gds;

use App\Traits\Jwt;
use Illuminate\Foundation\Auth\User as Authenticatable;

class usuarios extends Authenticatable
{

    use Jwt;

    protected $connection = 'oracle_gds';
    protected $table = 'USUARIOS';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'cui',
        'password',
        'nombre',
        'id_dependencia',
        'id_rol',
    ];

    protected $hidden = [
        'password',
    ];

    //RELACIONES

    public function menus() {
        return $this->belongsToMany(menu_paginas::class,'paginas_x_usuario','id_usuario','id_menu');
    }

    public function role() {
        return $this->belongsTo(roles::class,'id_rol','id_rol');
    }

    public function dependencia() {
        return $this->belongsTo(dependencias::class,'id_dependencia','id_dependencia');
    }

}
