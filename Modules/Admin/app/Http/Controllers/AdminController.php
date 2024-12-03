<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user   = User::find(Auth::id());
        $result = [
            'name' => $user->name
        ];
        $result['role_id'] = $user->roles[0]->id;
        $result['role_name']    = $user->roles[0]->name;

        /*
         *
         * Menampilkan data user lengkap dengan jabatannya 
         * 
        $users  = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->join('users', 'role_user.user_id', '=', 'users.id')
            ->select('users.name as nama_pengguna', 'roles.name as nama_jabatan')
            ->get();
        */

        /**
         * 
         * Menampilkan jumlah user
         */
        $user_count = DB::table('users')->count();

        return view('admin::dashboard.index', [
            'data'         => $result,
            'jml_pengguna' => $user_count,
        ]);
    }
}
