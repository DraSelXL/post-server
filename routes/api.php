<?php

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

// An API to manipulate the Users from the database.
Route::controller(UserController::class)->prefix('/users')->group(function () {
    Route::get('/list', 'getUserList');
    Route::post('/create', 'createUser');
    Route::put('/update', 'updateUser');
    Route::delete('/delete', 'deleteUser');
});
