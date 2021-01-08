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
 * Route protegé
 */
Route::middleware(['auth:sanctum'])->group(function() {

    /**
     * profil
     */
    Route::get('/user','UserController@get');
    
    /**
     * box
     */
    Route::get('/boxes','BoxController@get');
    Route::get('/boxes/{id}','BoxController@find');
    Route::post('/boxes/add-phone','BoxController@addPhone');
    Route::post('/boxes/edit-access','BoxController@editAccess');
});

