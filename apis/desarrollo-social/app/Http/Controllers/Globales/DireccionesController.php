<?php

namespace App\Http\Controllers\Globales;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\grupos_x_zona;
use App\Models\adm_gds\zonas;
use Illuminate\Http\Request;

class DireccionesController extends Controller
{
    public function grupo_zona(int $id_zona, int $id_grupo) {
        try {
            $grupos_zona = grupos_x_zona::where('id_zona',$id_zona)
                ->where('id_grupo',$id_grupo)
                ->get();
            return response($grupos_zona);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function zonas() {
        try {
            return response(zonas::all());
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
