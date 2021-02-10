<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\BoxController;
use App\Http\Controllers\Admin\EasyController;
use App\Http\Controllers\WebhookController;

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
Route::any('/webhook', [WebhookController::class, 'index'])->name('webhook');
Route::any('/login', [LoginController::class, 'login'])->name('login');
Route::any('/password', [LoginController::class, 'password'])->name('password');
Route::any('/password_ok', [LoginController::class, 'passwordOk'])->name('password_ok');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get(
        '/',
        [DashboardController::class, 'index']
    )->name('dashboard_index');

    /**
     * Gestion utilisateur
     */
    Route::get(
        '/users',
        [UserController::class, 'index']
    )->name('user_index');

    
    Route::get(
        '/users_json',
        [UserController::class, 'indexjson']
    )->name('user_index_json');

    Route::post(
        '/users',
        [UserController::class, 'add']
    );

    Route::get(
        '/users/{id}/delete',
        [UserController::class, 'delete']
    )->name('user_delete');

    Route::get(
        '/users/{id}/view',
        [UserController::class, 'view']
    )->name('user_view');


    /**
     * Gestion box
     */
    
    Route::get(
        '/boxes',
        [BoxController::class, 'index']
    )->name('box_index');
    
    Route::post(
        '/boxes',
        [BoxController::class, 'add']
    );

    Route::post(
        '/boxes/attach',
        [BoxController::class, 'attach']
    )->name('box_attach');

    Route::get(
        '/boxes/{id}/delete',
        [BoxController::class, 'delete']
    )->name('box_delete');

    Route::get(
        '/boxes/{id}/modifier',
        [BoxController::class, 'edit']
    )->name('box_edit');

    Route::post(
        '/boxes/pass',
        [BoxController::class, 'pass']
    )->name('box_pass');

    /**
     * Gestion Easy
     */

    Route::get(
        '/easies',
        [EasyController::class, 'index']
    )->name('easy_index');
    
    Route::post(
        '/easies',
        [EasyController::class, 'add']
    );

    Route::post(
        '/easies/attach',
        [EasyController::class, 'attach']
    )->name('easy_attach');

    Route::get(
        '/easies/{id}/delete',
        [EasyController::class, 'delete']
    )->name('easy_delete');

    Route::post(
        '/easies/pass',
        [EasyController::class, 'pass']
    )->name('easy_pass');

});
