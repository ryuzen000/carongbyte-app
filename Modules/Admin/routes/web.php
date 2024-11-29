<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\ProfileController;
use Modules\Admin\Http\Middleware\CheckAuthMiddleware as CheckAuth;
use Modules\Admin\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::group([], function () {
    Route::resource('admin', AdminController::class)->names('admin');
});*/

Route::get('dashboard', function () {
    return view('admin::dashboard');
});

Route::get('cr-test', function () {
    return cr_test();
});

Route::group(['namespace' => 'Modules\Admin\Http\Controllers'], function () {
    Route::name('admin.')->group(function () {
        Route::middleware(CheckAuth::class)->group(function () {
            Route::prefix('carong-admin')->group(function () {
                //Route::get('/', [AdminController::class, 'index'])->name('index');
                Route::get('', function () {
                    return redirect()->route('admin.dashboard');
                })->name('index');
                Route::get('dashboard', [
                    'as'   => 'dashboard',
                    'uses' => 'AdminController@index',
                ]);

                //Route::get('operators', [AdminController::class, 'operators'])->name('operators');

                Route::prefix('profile')->group(function () {
                    Route::get('', [
                        'as'   => 'profile.index',
                        'uses' => 'ProfileController@index',
                    ]);

                    Route::match(['get', 'post'], 'edit', [
                        'as'   => 'profile.edit',
                        'uses' => 'ProfileController@edit',
                    ]);

                    Route::match(['get', 'post'], 'edit-ajax', [
                        'as'   => 'profile.edit-ajax',
                        'uses' => 'ProfileController@edit_ajax',
                    ]);

                    Route::match(['get', 'post'], 'change-password', [
                        'as'   => 'profile.change-password',
                        'uses' => 'ProfileController@change_password',
                    ]);
                });

                Route::get('user', function () {
                    return view('admin::user.index');
                })->name('user');
            });

            /*Route::prefix('new-user')->group(function () {
            $user = User::create([
                'name'     => 'Charles Xavier',
                'email'    => 'charles@xmen.com',
                'password' => Hash::make('admin')
            ]);

            return $user;
        });*/

            /*Route::prefix('operator')->group(function () {
            Route::get('/', [OperatorsController::class, 'index'])->name('operator.index');
            Route::get('/new', [OperatorsController::class, 'new'])->name('operator.new');
            Route::post('/store', [OperatorsController::class, 'store'])->name('operator.store');
        });

        Route::prefix('product')->group(function () {
            Route::get('/', [ProductsController::class, 'index'])->name('product.index');
            Route::get('/new', [ProductsController::class, 'new'])->name('product.new');
            Route::post('/store', [ProductsController::class, 'store'])->name('product.store');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', function () {
                //$data = auth()->user()->id;
                $data = User::find(Auth::id());
                return view('admin::profile', [
                    'name'  => $data->name,
                    'email' => $data->email
                ]);
            });
        });*/
        });
    });
});

Route::name('login-register.')->group(function () {
    Route::get('/login', function () {
        if (Auth::check())
            return redirect('carong-admin');

        //return "Admin Login";
        return view('admin::login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        /*$messages = [
            "email.required" => "Kolom email/username tidak boleh kosong!",
            "email.email" => "Email is not valid",
            "email.exists" => "Email doesn't exists",
            "password.required" => "Kolom password tidak boleh kosong!",
            "password.min" => "Password must be at least 6 characters"
        ];*/

        $username = isset($request->email) ? $request->email : null;
        $password = isset($request->password) ? $request->password : null;

        $user = User::where('email', $username)->orWhere('username', $username)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'email/username tidak ditemukan']);
        }

        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]/*, $messages*/);

        if (
            Auth::attempt(['email' => $user->email, 'password' => $password]) ||
            Auth::attempt(['username' => $user->username, 'password' => $password])
        ) {
            $request->session()->regenerate();

            return redirect()->intended('carong-admin');
        } else {
            return redirect()->back()->withErrors(['password' => 'password salah!']);
        }
    })->name('authenticate');
});

Route::get('/logout', function () {
    Session::flush();

    Auth::logout();

    return redirect('login');
})->name('logout');
