<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JudgeController;
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
Route::get('/track/{name}/leaderboard', [ParticipantController::class, 'leaderboard'])->middleware(['auth:sanctum', 'trackExists']); // TESTED

Route::prefix('admin')->middleware(['auth:sanctum', 'hasRole:admin'])
    ->controller(AdminController::class)->group(function() {
    Route::prefix('user')->group(function() {
        Route::post('/create-participant', 'create_participant'); // TESTED
        Route::post('/create-judge', 'create_judge'); // TESTED
        Route::delete('/delete/{id}', 'delete_user'); // TESTED
    });

    Route::prefix('challenge')->group(function() {
        Route::get('/', 'get_challenges'); // TESTED
        Route::post('/create', 'create_challenges'); // TESTED
        Route::post('/lock/{id}', 'lock_challenge')->middleware('challengeExist'); // TESTED
        Route::post('/unlock/{id}', 'unlock_challenge')->middleware('challengeExist'); // TESTED
        Route::put('/update/{id}', 'update_challenge')->middleware('challengeExist'); // TESTED
        Route::delete('/delete/{id}', 'delete_challenge')->middleware('challengeExist'); // TESTED
    });

    Route::prefix('track')->group(function() {
        Route::get('/', 'get_tracks'); // TESTED
        Route::post('/create', 'create_track'); // TESTED
        Route::post('/lock-all', 'lock_tracks'); // TESTED
        Route::post('/unlock-all', 'unlock_tracks'); // TESTED
        Route::post('/lock/{id}', 'lock_track')->middleware('trackExists'); // TESTED
        Route::post('/unlock/{id}', 'unlock_track')->middleware('trackExists'); // TESTED
        Route::delete('/delete/{id}', 'delete_track')->middleware('trackExists'); // TESTED
    });
});


Route::prefix('participant')->middleware(['auth:sanctum', 'hasRole:participant'])
    ->controller(ParticipantController::class)->group(function() {
    Route::prefix('track')->group(function () {
        Route::get('/', 'get_tracks'); // TESTED
        Route::get('/{track}/challenges', 'get_track_challenges'); // TESTED
    });

    Route::prefix('challenge')->group(function() {
        Route::get('/{id}/download', 'download_attachment')->middleware(['challengeExist', 'challengeNotLocked']);
        Route::get('/{id}', 'get_challenge')->middleware(['challengeExist', 'challengeNotLocked']);
        Route::get('/{id}/submissions', 'get_submissions')->middleware('challengeExist'); // TESTED
        Route::post('/{id}/submit', 'submit_challenge')->middleware(['challengeExist', 'trackNotLocked', 'challengeNotLocked', 'canSubmit']); // TESTED
    });
    Route::prefix('submission')->group(function()  {
        Route::post('/{id}/cancel', 'cancel_submission')->middleware(['submissionExists', 'submissionBelongsToAuth', 'submissionHasStatus:pending']); // TESTED
    });
});


Route::prefix('judge')->middleware(['auth:sanctum', 'hasRole:judge'])
    ->controller(JudgeController::class)->group(function() {
    Route::get('/submissions', 'get_submissions');
    Route::post('/submission/{id}/assign-judge', 'assign_judge')->middleware(['submissionExists', 'submissionHasStatus:pending']);
    Route::post('/submission/{id}/judge', 'judge_submission')->middleware(['submissionExists', 'canValidateSubmission', 'submissionHasStatus:judging']);
});
