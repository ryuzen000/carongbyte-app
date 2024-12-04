<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $user   = User::find(Auth::id());
        $result = [
            'name' => $user->name
        ];
        $result['role_id']   = $user->roles[0]->id;
        $result['role_name'] = $user->roles[0]->name;

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
