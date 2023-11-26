<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::get('user', 'user');
    Route::put('user/{id}', 'updateUser');
    Route::delete('destroy/{id}', 'destroy');
    Route::put('updateUser', 'updateUser');
    Route::post('updateVoteStatus', 'updateVoteStatus');
    Route::post('updateVoteStatusTerkiller', 'updateVoteStatusTerkiller');
    Route::post('updateVoteStatusTerinspiratif', 'updateVoteStatusTerinspiratif');
    Route::get('getGuru', 'getGuru');
    Route::post('guruTerasik', 'guruTerasik');
    Route::post('vote', 'vote');
    Route::get('category', 'category');
    Route::post('postSuara', 'postSuara');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

