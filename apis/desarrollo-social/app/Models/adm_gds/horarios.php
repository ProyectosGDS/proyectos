<?php

namespace App\Models\adm_gds;

use Illuminate\Database\Eloquent\Model;

class horarios extends Model
{
    protected $connection = 'oracle_gds';
    protected $table = 'HORARIOS';
    protected $primaryKey = 'id_horario';
    protected $appends = ['full'];
    public $timestamps = false;

    protected $fillable = [
        'hora_ini',
        'hora_fin',
        'lun',
        'mar',
        'mie',
        'jue',
        'vie',
        'sab',
        'dom',
        'estatus',
        'usuario',
        'fechau',
    ];

    public function getFullAttribute() {
        
        $hora = $this->hora_ini .' a '.$this->hora_fin;

        $dias = [
            $this->lun,
            $this->mar,
            $this->mie,
            $this->jue,
            $this->vie,
            $this->sab,
            $this->dom,
        ];

        $dias = array_filter($dias, function ($dia) {
            return !is_null($dia) && $dia !== '';
        });

        return $hora.' '.implode('-', $dias);

    }
}
