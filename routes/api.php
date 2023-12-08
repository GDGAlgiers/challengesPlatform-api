<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JudgeController;
use App\Http\Controllers\API\ParticipantController;
use App\Http\Controllers\API\GeneralController;
use App\Http\Controllers\VerifyEmailController;
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

Route::post('/email/verification-notification', [VerifyEmailController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');


Route::middleware(['throttle:api'])->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/tracks', [GeneralController::class, 'get_track_types']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/track/{type}/leaderboard', [ParticipantController::class, 'leaderboard'])->middleware(['auth:sanctum', 'verified']);

    Route::prefix('admin')->middleware(['auth:sanctum', 'hasRole:admin'])
        ->controller(AdminController::class)->group(function() {
        Route::get('/stats', 'get_stats');
        Route::prefix('user')->group(function() {
            Route::get('/', 'get_all_users');
            Route::post('/create-participant', 'create_participant');
            Route::post('/create-judge', 'create_judge');
            Route::delete('/{id}/delete', 'delete_user');
        });

        Route::prefix('challenge')->group(function() {
            Route::get('/', 'get_challenges');
            Route::post('/create', 'create_challenges');
            Route::post('/{id}/lock', 'lock_challenge')->middleware(['challengeExists', 'challengeNotLocked']);
            Route::post('/{id}/unlock', 'unlock_challenge')->middleware('challengeExists');
            Route::post('/{id}/update', 'update_challenge')->middleware('challengeExists');
            Route::delete('{id}/delete', 'delete_challenge')->middleware('challengeExists');
        });

        Route::prefix('track')->group(function() {
            Route::get('/', 'get_tracks');
            Route::post('/create', 'create_track');
            Route::post('/{id}/update', 'update_track')->middleware('trackExists');
            Route::post('/lock-all', 'lock_tracks');
            Route::post('/unlock-all', 'unlock_tracks');
            Route::post('/{id}/lock', 'lock_track')->middleware('trackExists');
            Route::post('/{id}/unlock', 'unlock_track')->middleware('trackExists');
            Route::delete('/{id}/delete', 'delete_track')->middleware('trackExists');
        });

            Route::prefix('team')->group(function () {
                Route::get('/', 'get_teams');
                Route::get('/{id}', 'get_team')->middleware('teamExists');
                Route::post('/create', 'create_team');
                Route::post('/{id}/update', 'update_team')->middleware('teamExists');
                Route::delete('/{id}/delete', 'delete_team')->middleware('teamExists');
                Route::post('/{id}/add-member', 'add_member')->middleware('teamExists');
                Route::post('/remove-member', 'remove_member');
                


            });
    });

    Route::prefix('participant')->middleware(['auth:sanctum', 'hasRole:participant', 'verified'])
        ->controller(ParticipantController::class)->group(function() {
        Route::prefix('track')->group(function () {
            Route::get('/', 'get_tracks');
            Route::get('/{id}/challenges', 'get_track_challenges')->middleware(['trackExists', 'trackNotLocked', 'hasAccessToTrack']);
        });

        Route::prefix('challenge')->middleware(['challengeExists'])->group(function() {
            Route::get('/{id}/download', 'download_attachment')->middleware(['challengeTrackNotLocked', 'challengeNotLocked']);
            Route::get('/{id}', 'get_challenge')->middleware(['challengeTrackNotLocked', 'challengeNotLocked']);
            Route::get('/{id}/submissions', 'get_submissions');
            Route::post('/{id}/submit', 'submit_challenge')->middleware(['challengeTrackNotLocked', 'challengeNotLocked', 'canSubmit']);
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

