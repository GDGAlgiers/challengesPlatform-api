<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JudgeController;
use App\Http\Controllers\API\ParticipantController;
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

Route::middleware(['throttle:api'])->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/track/{name}/leaderboard', [ParticipantController::class, 'leaderboard'])->middleware(['auth:sanctum']);

    Route::prefix('admin')->middleware(['auth:sanctum', 'hasRole:admin'])
        ->controller(AdminController::class)->group(function() {
        Route::get('/stats', 'get_stats');
        Route::prefix('user')->group(function() {
            Route::get('/', 'get_all_users');
            Route::post('/create-participant', 'create_participant');
            Route::post('/create-judge', 'create_judge');
            Route::delete('/delete/{id}', 'delete_user');
        });

        Route::prefix('challenge')->group(function() {
            Route::get('/', 'get_challenges');
            Route::post('/create', 'create_challenges');
            Route::post('/lock/{id}', 'lock_challenge')->middleware(['challengeExist', 'challengeNotLocked']);
            Route::post('/unlock/{id}', 'unlock_challenge')->middleware('challengeExist');
            Route::post('/update/{id}', 'update_challenge')->middleware('challengeExist');
            Route::delete('/delete/{id}', 'delete_challenge')->middleware('challengeExist');
        });

        Route::prefix('track')->group(function() {
            Route::get('/', 'get_tracks');
            Route::post('/create', 'create_track');
            Route::post('/update/{id}', 'update_track')->middleware('trackExists');
            Route::post('/lock-all', 'lock_tracks');
            Route::post('/unlock-all', 'unlock_tracks');
            Route::post('/lock/{id}', 'lock_track')->middleware('trackExists');
            Route::post('/unlock/{id}', 'unlock_track')->middleware('trackExists');
            Route::delete('/delete/{id}', 'delete_track')->middleware('trackExists');
        });
    });

    Route::prefix('participant')->middleware(['auth:sanctum', 'hasRole:participant'])
        ->controller(ParticipantController::class)->group(function() {
        Route::prefix('track')->group(function () {
            Route::get('/', 'get_tracks');
            Route::get('/{id}/challenges', 'get_track_challenges')->middleware(['trackExists', 'trackNotLocked', 'hasAccessToTrack']);
        });

        Route::prefix('challenge')->middleware(['challengeExist'])->group(function() {
            Route::get('/{id}/download', 'download_attachment')->middleware(['trackNotLocked', 'challengeNotLocked']);
            Route::get('/{id}', 'get_challenge')->middleware(['trackNotLocked', 'challengeNotLocked']);
            Route::get('/{id}/submissions', 'get_submissions');
            Route::post('/{id}/submit', 'submit_challenge')->middleware(['trackNotLocked', 'challengeNotLocked', 'canSubmit']);
        });
        Route::prefix('submission')->group(function()  {
            Route::get('/', 'get_all_submissions');
            Route::post('/{id}/cancel', 'cancel_submission')->middleware(['submissionExists', 'submissionBelongsToAuth', 'submissionHasStatus:pending']);
        });
    });

    Route::prefix('judge')->middleware(['auth:sanctum', 'hasRole:judge'])
        ->controller(JudgeController::class)->group(function() {
        Route::get('/submissions', 'get_submissions');
        Route::post('/submission/{id}/assign-judge', 'assign_judge')->middleware(['submissionExists', 'submissionHasStatus:pending']);
        Route::post('/submission/{id}/judge', 'judge_submission')->middleware(['submissionExists', 'submissionHasStatus:judging', 'canValidateSubmission']);
    });
});

