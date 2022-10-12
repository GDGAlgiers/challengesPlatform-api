<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ParticipantController;
use App\Models\User;
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
Route::post('/login', [AuthController::class, 'login']); // TESTED
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // TESTED


Route::prefix('admin')->middleware(['auth:sanctum', 'hasRole:admin'])
    ->controller(AdminController::class)->group(function() {
    Route::prefix('user')->group(function() {
        Route::post('/create-participant', 'create_participant'); // TESTED
        Route::post('/create-judge', 'create_judge'); // TESTED
        Route::delete('/delete-user/{id}', 'delete_user'); // TESTED
    });

    Route::prefix('challenge')->group(function() {
        Route::get('/', 'get_challenges'); // TESTED
        Route::post('/create', 'create_challenges'); // TESTED
        Route::put('/update/{id}', 'update_challenge'); // TESTED
        Route::delete('/delete/{id}', 'delete_challenge'); // TESTED
    });

    Route::prefix('track')->group(function() {
        Route::get('/', 'get_tracks'); // TESTED
        Route::post('/create', 'create_track'); // TESTED
        Route::post('/lock', 'lock_tracks'); // TESTED
        Route::post('/unlock', 'unlock_tracks'); // TESTED
        Route::delete('/delete/{id}', 'delete_track'); // TESTED
    });
});


Route::prefix('participant')->middleware(['auth:sanctum', 'hasRole:participant'])
    ->controller(ParticipantController::class)->group(function() {
    Route::prefix('track')->group(function () {
        Route::get('/', 'get_tracks'); // TESTED
        Route::get('/{track}/challenges', 'get_track_challenges'); // TESTED
    });

    Route::prefix('challenge')->group(function() {
        Route::post('/{id}/submit', 'submit_challenge')->middleware(['challengeExist', 'trackNotLocked', 'canSubmit']);
    });
});



