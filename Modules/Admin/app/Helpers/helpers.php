<?php

use Modules\Admin\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * 
 * 
 * @source https://medium.com/@techsolutionstuff/how-to-create-custom-helper-function-in-laravel-11-85f8e24728eb
 */
if (!function_exists('cr_test')) {
    function cr_test()
    {
        return "Carong Test";
    }
}

if (!function_exists('cr_auth_name')) {
    function cr_auth_name()
    {
        $user = User::find(Auth::id());
        return $user->name;
    }
}

if (!function_exists('cr_auth_foto')) {
    function cr_auth_foto()
    {
        $users = DB::table('usermeta')
            ->where('user_id', '=', Auth::id())
            ->where('key', '=', 'cr_user_foto')
            ->get();

        return $users[0]->value;
    }
}

if (!function_exists('cr_get_usermeta')) {
    function cr_get_usermeta($user_id, $key)
    {
        $prefix = "cr_";
        $result = null;

        if ($user_id && $key) {
            $result = DB::table('usermeta')
                ->where('user_id', '=', $user_id)
                ->where('key', '=', $prefix . $key)
                ->get();
        }

        return $result[0]->value;
    }
}

if (!function_exists('cr_nav_items')) {
    function cr_nav_items()
    {
        $result = [
            [
                'name'  => 'Dashboard',
                'route' => 'admin.dashboard',
                'icon'  => 'fas fa-tachometer-alt'
            ],
            [
                'name'  => 'User',
                'route' => 'admin.user',
                'icon'  => 'fas fa-users'
            ]
        ];
        return $result;
    }
}
