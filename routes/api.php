<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ParticipantController;
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

// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::prefix('admin')->middleware(['auth:sanctum', 'hasRole:admin'])
    ->controller(AdminController::class)->group(function() {
    Route::prefix('user')->group(function() {
        Route::post('/create-participant', 'create_participant');
        Route::post('/create-judge', 'create_judge');
        Route::delete('/delete-user/{id}', 'delete_user');
    });

    Route::prefix('challenge')->group(function() {
        Route::get('/', 'get_challenges');
        Route::post('/create', 'create_challenges');
        Route::put('/update/{id}', 'update_challenge');
        Route::delete('/delete/{id}', 'delete_challenge');
    });

    Route::prefix('track')->group(function() {
        Route::get('/', 'get_tracks');
        Route::post('/create', 'create_track');
        Route::post('/lock', 'lock_tracks');
        Route::post('/unlock', 'unlock_tracks');
        Route::delete('/delete/{id}', 'delete_track');
    });
});


Route::prefix('participant')->middleware(['auth:sanctum', 'hasRole:participant'])
    ->controller(ParticipantController::class)->group(function() {
    Route::prefix('track')->group(function () {
        Route::get('/test', function () {
            $authUser = auth()->user();
            return response()->json([
                'success' => true,
                'data' => $authUser->submissions
            ]);
        });
        Route::get('/', 'get_tracks');
        Route::get('/{track}/challenges', 'get_track_challenges');
    });

    Route::prefix('challenge')->group(function() {

    });
});


