<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AnnonceController;
use App\Http\Controllers\API\ApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(AnnonceController::class)->group(function () {
    Route::prefix('annonce')->group(function () {

        Route::middleware(['role:benevole'])->group(function () {
            Route::get('/', 'index');
        });

        Route::middleware(['role:organisateur'])->group(function () {
            Route::post('store', 'store');
            Route::delete('{id}/delete', 'delete');
            Route::put('{annonce}/update', 'update');
        });
    });
});



Route::controller(ApplicationController::class)->group(function () {

    Route::middleware(['role:organisateur'])->group(function () {
        Route::post('/application/{id}/refuse', 'refuse');
        Route::post('/application/{id}/confirm', 'confirm');
    });

    Route::middleware(['role:benevole'])->group(function () {
        Route::post('application', 'store');

    });
});