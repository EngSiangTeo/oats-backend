<?php

use Pusher\Pusher;
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

Route::middleware('auth:api')->group(function () {
	Route::namespace('Chat')->name('chat.')->group(function () {
		Route::get('/messages', 'MessageController@index')->name('get_chats');
		Route::get('/message/{chatId}', 'MessageController@messagesByChatId')->name('get_chat');
		Route::post('/message/{chatId}', 'MessageController@store')->name('add_chat');
		Route::get('/chats', 'ChatController@index')->name('chats');
		Route::post('/startChat', 'ChatController@createNewConversation')->name('start_chat');
	});

	Route::namespace('Listing')->name('listing.')->group(function () {
		Route::get('/user-listings', 'ListingApiController@index')->name('get_user_listings');
		Route::get('/all-listings', 'ListingApiController@getAllListings')->name('get_all_listings');
	});
});
