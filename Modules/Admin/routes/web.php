<?php

use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\ProfileController;
use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Middleware\CheckAuthMiddleware as CheckAuth;
use Modules\Admin\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
                Route::get('', [
                    'as'   => 'index',
                    'uses' => 'AdminController@index'
                ]);

                Route::get('dashboard', [
                    'as'   => 'dashboard',
                    'uses' => 'AdminController@dashboard',
                ]);

                /**
                 * 
                 * 
                 * Profile route
                 */
                Route::prefix('profile')->group(function () {
                    Route::get('', [
                        'as'   => 'profile.index',
                        'uses' => 'ProfileController@index',
                    ]);

                    Route::match(['get', 'post'], 'edit', [
                        'as'   => 'profile.edit',
                        'uses' => 'ProfileController@edit',
                    ]);
                    Route::post('update', [
                        'as'  => 'profile.update',
                        'uses' => 'ProfileController@update',
                    ]);

                    Route::get('test-form', function () {
                        return view('admin::profile.test_req');
                    });

                    Route::match(['get', 'post'], 'edit-ajax', [
                        'as'   => 'profile.edit-ajax',
                        'uses' => 'ProfileController@edit_ajax',
                    ]);

                    Route::match(['get', 'post'], 'change-password', [
                        'as'   => 'profile.change-password',
                        'uses' => 'ProfileController@change_password',
                    ]);
                });

                /**
                 * 
                 * 
                 * User route
                 */
                Route::prefix('user')->group(function () {
                    Route::get('', [
                        'as'   => 'user.index',
                        'uses' => 'UserController@index',
                    ]);

                    Route::get('edit/{id}', [
                        'as'   => 'user.edit',
                        'uses' => 'UserController@edit',
                    ])->where(['id' => '[0-9]+']);
                });
            });

            /**
             * 
             * Contoh untuk membuat user baru
             */
            Route::get('new-user', function () {
                //$data = auth()->user()->id;
                $user = User::create([
                    'name'     => 'Charles Xavier',
                    'email'    => 'charles@xmen.com',
                    'password' => Hash::make('admin')
                ]);

                return $user;
            });
        });
    });
});

Route::group(['namespace' => 'Modules\Admin\Http\Controllers'], function () {
    Route::name('authentication.')->group(function () {
        Route::match(['get', 'post'], 'login', [
            'as'   => 'login',
            'uses' => 'AuthController@login',
        ]);

        Route::get('logout', [
            'as'   => 'logout',
            'uses' => 'AuthController@logout',
        ]);
    });
});
