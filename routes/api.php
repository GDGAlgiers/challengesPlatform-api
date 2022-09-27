<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::prefix('admin')->middleware(['auth:sanctum', 'hasRole:admin'])
    ->controller(AdminController::class)->group(function() {
    Route::prefix('user')->group(function() {
        Route::post('/create-judge', 'create_judge');
        Route::delete('/delete-user/{id}', 'delete_user');
    });

    Route::prefix('challenge')->group(function() {
        Route::get('/', 'get_challenges');
        Route::post('/create', 'create_challenges');
        Route::put('/update/{id}', 'update_challenge');
        Route::delete('/delete/{id}', 'delete_challenge');
    });
});

