<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\menu_paginas;
use Illuminate\Http\Request;

class MenuPaginasController extends Controller
{
    public function index() {
        try {
            $menu_paginas = menu_paginas::with(['children'])->whereNull('menu_id')->get();
            return response($menu_paginas);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function store(Request $request) {
        try {
            //code...
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }


    public function show(string $id) {
        try {
            //code...
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }


    public function update(Request $request, string $id) {
        try {
            //code...
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }

    public function destroy(string $id) {
        try {
            //code...
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
}
