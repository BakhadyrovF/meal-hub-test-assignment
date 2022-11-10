<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
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

Route::group(['prefix' => 'users', 'as' => 'users.', 'controller' => UserController::class], function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::post('/{id}/to-cart', 'addToCart')->name('to-cart');
    Route::delete('/{id}', 'delete')->name('delete');

    Route::group(['as' => 'orders.', 'controller' => OrderController::class], function () {
        Route::post('/{id}/orders', 'store')->name('store');
    });

    /** Additional routes that are not required in test-assignment */
    Route::post('/play-secret-santa', 'playSecretSanta')->name('play-secret-santa');
});


