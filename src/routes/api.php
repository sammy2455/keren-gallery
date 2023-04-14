<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['prefix' => 'v1'], function ($router) {
    Route::group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers\Authentication', 'as' => 'authentication.'], function ($router) {
        Route::post('/login', 'LoginController')->name('login');
        Route::post('/logout', 'LogoutController')->name('logout')->middleware('auth:sanctum');
        Route::post('/refresh', 'RefreshController')->name('refresh')->middleware('auth:sanctum');
        Route::post('/me', 'MeController')->name('me')->middleware('auth:sanctum');
    });

    Route::group(['prefix' => 'image', 'namespace' => 'App\Http\Controllers\Image', 'as' => 'image.'], function ($router) {
        Route::post('/upload', 'UploadController')->name('upload')->middleware('auth:sanctum');
        Route::delete('/{id}', 'DeleteController')->name('delete')->middleware('auth:sanctum');
    });
});
