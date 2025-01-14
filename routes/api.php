<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/users', [UserController::class, 'create']);

Route::post('/users/{id}/score', [UserController::class, 'addPoints']);

Route::get('/leaderboard/top', [UserController::class, 'getUsersTop']);

Route::get('/leaderboard/rank/{id}', [UserController::class, 'getUserRank']);