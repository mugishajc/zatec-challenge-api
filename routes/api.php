<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
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

Route::post('/users', [UsersController::class, 'store']);
Route::put('/users/favourite', [UsersController::class, 'update']); // { email, type ie album/artist, url, action i.e add/remove }

Route::get('/users', [UsersController::class, 'index']);
Route::get('/user', [UsersController::class, 'show']);
Route::delete('/users/{email}', [UsersController::class, 'destroy']);
