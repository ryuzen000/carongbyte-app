<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $messages  = [
                'email.required'    => 'Email tidak boleh kosong',
                'password.required' => 'Password tidak boleh kosong',
            ];
            $validated = $request->validate([
                'email'    => "required|max:255",
                'password' => 'required|max:255',
            ], $messages);

            $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();

            if (!$user) {
                return redirect()
                    ->back()
                    ->withErrors(
                        ['email' => 'Email/Username tidak ditemukan']
                    );
            }

            if (
                Auth::attempt(['email' => $user->email, 'password' => $request->password]) ||
                Auth::attempt(['username' => $user->username, 'password' => $request->password])
            ) {
                $request->session()->regenerate();

                return redirect()->intended('carong-admin');
            } else {
                return redirect()
                    ->back()
                    ->withErrors(
                        ['password' => 'Password salah']
                    );
            }
        }

        if (Auth::check())
            return redirect('carong-admin');

        //return "Admin Login";
        return view('admin::login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('login');
    }
}
