<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\adm_gds\usuarios;
use App\Rules\ValidateCui;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function authenticate(Request $request) {
        $request->validate([
            'cui' => ['required','numeric','digits:13',new ValidateCui,'exists:usuarios,cui'],
            'password' => 'required|string|min:8'
        ]);

        try {

            $aud = $request->header('Origin');
            $receivers = config('jwt.receivers');

            if(in_array($aud,$receivers)){

                $user = usuarios::with(['role.permisos','dependencia:id_dependencia,nombre,tiene_escuelas'])
                            ->where('cui',$request->cui)->first();
                $user->makeVisible('password');

            
                if(Hash::check(base64_decode($request->password),$user->password)){

                    $user->makeHidden('password');

                    Auth::login($user);
                    
                    $payload = [
                        'sub' => $user->id_usuario,
                    ];

                    $accessToken = $user->createToken($payload, $aud);

                    if($accessToken) {

                        $cookie = cookie(base64_encode('access_token'), $accessToken, config('jwt.expired_token'), '/', null, null, false);

                        $usuario = $this->permisosApp($user,$request->header('App'));
                        $usuario = $this->menus($usuario);

                        return response(base64_encode($usuario))->withCookie($cookie);
                    }

                    return response('Unauthorized',422);
                }
            }

            return response(['errors' => ['credenciales' => ['Credenciales invalidas']] ], 422);

        } catch (\Throwable $th) {
            return response($th->getMessage());
        }

        
    }

    public function verifyAuth(Request $request) {

        $user = auth()->user()->load(
            'role.permisos',
            'dependencia:id_dependencia,nombre,tiene_escuelas',
        );

        $user = $this->permisosApp($user,$request->header('App'));

        $user = $this->menus($user);

        return response(base64_encode($user));

    }

    public function permisosApp($user, $app) {

        $user = $user;

        if( isset($user->role) ) {
            
            $permisos = [];
            
            foreach ( $user->role->permisos as $permiso ) {
                if( $permiso->app === $app ) {
                    $permisos[] = $permiso->nombre;
                }
            }
 

            $user->rol = $user->role->nombre;
            
            unset($user->role);

            $user->permisos = $permisos;

        }

        //ESTO IDENTIFICA SI HAY ESCUELAS O NO DE LA DIRECCION O DEPENDENCIA A LA PERTENECE EL USUARIO
        // $escuela = DiDirecciones::with('escuelas')->where('id',$user->empleado->di_direccion_id)->first();
        // $user->escuelas = $escuela->escuelas->select(['id','nombre']);
        
        return $user;
    }

    public function menus($user) {

        $user = $user;

        $menus = $user->load('menus.parent')->menus;

        $groupMenu = $menus->groupBy('menu_id');

        $menu = collect();
        $submenu = collect();

        foreach ($groupMenu as $children) {
            foreach ($children as $child) {
                if ($child->parent) {
                    $child->parent->submenu = collect();
                    $menu->push($child->parent);
                } else {
                    $menu->push($child);
                }
        
                unset($child->parent, $child->pivot);
                $submenu->push($child);
            }
        }

        $menu = $menu->unique('id_menu');

        $menu->each(function ($parent) use ($submenu) {
            $parent->submenu = $submenu->where('menu_id', $parent->id_menu)->values();
        });

        unset($user->menus);

        $user->menus = $menu->sortBy('orden')->values()->all();    
        return $user;
    }

    public function logout() {
        Auth::logout();
        $cookie = Cookie::forget(base64_encode('access_token'));
        return response(['message' => 'Sesión cerrada exitosamente'])
                         ->withCookie($cookie);
    }
}
