<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Middleware\CheckAuthMiddleware as CheckAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

Route::group([], function () {
    Route::resource('admin', AdminController::class)->names('admin');
});

Route::get('dashboard', function () {
    return view('admin::dashboard');
});

Route::name('admin.')->group(function () {
    Route::middleware(CheckAuth::class)->group(function () {
        Route::prefix('carong-admin')->group(function () {
            Route::get('/', function () {
                return "Hello, World!";
            })->name('index');

            //Route::get('/', [AdminController::class, 'index'])->name('index');

            //Route::get('operators', [AdminController::class, 'operators'])->name('operators');
        });

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

Route::name('login-register.')->group(function () {
    Route::get('/login', function () {
        if (Auth::check())
            return redirect('carong-admin');

        //return "Admin Login";
        return view('admin::login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('admin');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    })->name('authenticate');
});

Route::get('/logout', function () {
    Session::flush();

    Auth::logout();

    return redirect('login');
})->name('logout');
