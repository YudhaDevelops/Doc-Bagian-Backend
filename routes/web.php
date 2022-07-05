<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login/relawan');

Route::controller(UserController::class)->group(function(){
    //------relawan
    Route::get('/login/relawan','formLogin_relawan')->name('login.relawan');
    Route::get('/register/relawan','formRegis_relawan')->name('regis.relawan');
    Route::post('/register/relawan','register_relawan')->name('regis.relawan');
    
    //------Admin
    // register
    Route::get('/register/admin','formRegis_admin')->name('regis.admin');
    Route::post('/register/admin','register_admin')->name('regis.admin');

    // login
    Route::get('/login/admin','formLog_admin')->name('log.admin');
    Route::post('/login/admin','login_admin')->name('log.admin');
});