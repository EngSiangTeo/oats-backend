<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Account')->name('account.')->group(function () {
    Route::post('/loginByEmail', 'Login\LoginApiController@loginByEmail')->name('login_by_email');
    Route::post('/registerByEmail', 'User\Register\RegisterApiController@registerByEmail')->name('register');
});