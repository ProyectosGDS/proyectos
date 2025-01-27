<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class menu_paginas extends Model
{
    protected $connection = 'oracle_gds';    
    protected $table = 'MENU_PAGINAS';
    protected $primaryKey = 'id_menu';
    public $timestamps = false;

    protected $appends = ['active'];

    protected $fillable = [
        'nombre_pagina',
        'link_pagina',
        'icon',
        'menu_id',
        'orden',
    ];

    public function children() {
        return $this->hasMany(menu_paginas::class,'menu_id','id_menu');
    }

    public function  parent() {
        return $this->belongsTo(menu_paginas::class,'menu_id','id_menu');
    }

    public function getActiveAttribute(){
        return false;
    }

}
