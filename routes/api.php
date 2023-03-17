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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Auth route
 */
Route::post('/inscription','LoginController@inscription');
Route::post('/connexion','LoginController@connexion');
Route::post('/password','LoginController@password');

/**
 * RTU route
 */
Route::post('/RTU/inscription','RtuController@inscription');
Route::post('/RTU/connexion','RtuController@connexion');
Route::post('/RTU/password','RtuController@password');


/**
* fix
*/
Route::any('/twilio','TwilioController@get')->name('twilio');

/**
 * Route protegé
 */
Route::middleware(['auth:sanctum'])->group(function() {

    /**
     * profil
     */
    Route::get('/user','UserController@get');
    Route::post('/user','UserController@edit');
    /**
     * box
     */
    Route::get('/boxes','BoxController@get');
    Route::get('/boxes/phones','BoxController@getPhones');
    Route::get('/boxes/ordre','BoxController@getOrdre');
    Route::get('/boxes/recup','BoxController@recup');
    Route::post('/boxes/request-list-phone','BoxController@requestPhone');
    Route::post('/boxes/delete-phone','BoxController@delPhone');
    Route::get('/boxes/{id}','BoxController@find');
    Route::post('/boxes/add-phone','BoxController@addPhone');
    Route::post('/boxes/add-message','BoxController@addMessage');
    Route::post('/boxes/edit-access','BoxController@editAccess');
    Route::post('/boxes/edit-duration','BoxController@editDuration');
    Route::post('/boxes/sms','BoxController@editSMS');

    /**
     * serrures
     */
    Route::get('/easies/ordre','EasyController@getOrdre');
    Route::get('/easies/phones','EasyController@getPhones');
    Route::post('/easies/delete-phone','EasyController@delPhone');
    Route::get('/easies','EasyController@get');
    Route::get('/easies/{id}','EasyController@find');
    Route::post('/easies/add-phone','EasyController@addPhone');
    Route::post('/easies/edit-access','EasyController@editAccess');
    Route::post('/easies/edit-duration','EasyController@editDuration');
    Route::post('/easies/request-list-phone','EasyController@requestPhone');
    Route::post('/easies/sms','EasyController@editSMS');
});

