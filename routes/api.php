<?php



use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JudgeController;
use App\Http\Controllers\API\ParticipantController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Helpers\CSVReader;
use App\Mail\ChallengesAcceptance;
use App\Mail\HackathonCertificate;
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
    Route::post('/login', [AuthController::class, 'login']); // TESTED
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // TESTED
    Route::get('/track/{name}/leaderboard', [ParticipantController::class, 'leaderboard'])->middleware(['auth:sanctum']); // TESTED

    Route::prefix('admin')->middleware(['auth:sanctum', 'hasRole:admin'])
        ->controller(AdminController::class)->group(function() {
        Route::get('/stats', 'get_stats');
        Route::prefix('user')->group(function() {
            Route::get('/', 'get_all_users'); // TESTED
            Route::post('/create-participant', 'create_participant'); // TESTED
            Route::post('/create-judge', 'create_judge'); // TESTED
            Route::delete('/delete/{id}', 'delete_user'); // TESTED
        });

        Route::prefix('challenge')->group(function() {
            Route::get('/', 'get_challenges'); // TESTED
            Route::post('/create', 'create_challenges'); // TESTED
            Route::post('/lock/{id}', 'lock_challenge')->middleware('challengeExist'); // TESTED
            Route::post('/unlock/{id}', 'unlock_challenge')->middleware('challengeExist'); // TESTED
            Route::post('/update/{id}', 'update_challenge')->middleware('challengeExist'); // TESTED
            Route::delete('/delete/{id}', 'delete_challenge')->middleware('challengeExist'); // TESTED
        });

        Route::prefix('track')->group(function() {
            Route::get('/', 'get_tracks'); // TESTED
            Route::post('/create', 'create_track'); // TESTED
            Route::post('/{id}/update', 'update_track')->middleware('trackExists'); // TESTED
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
            Route::get('/{id}/challenges', 'get_track_challenges')->middleware('trackExists'); // TESTED
        });

        Route::prefix('challenge')->group(function() {
            Route::get('/{id}/download', 'download_attachment')->middleware(['challengeExist', 'trackNotLocked', 'challengeNotLocked']);
            Route::get('/{id}', 'get_challenge')->middleware(['challengeExist', 'trackNotLocked', 'challengeNotLocked']); // TESTED
            Route::get('/{id}/submissions', 'get_submissions')->middleware('challengeExist'); // TESTED
            Route::post('/{id}/submit', 'submit_challenge')->middleware(['challengeExist', 'trackNotLocked', 'challengeNotLocked', 'canSubmit']); // TESTED
        });
        Route::prefix('submission')->group(function()  {
            Route::get('/', 'get_all_submissions'); // TESTED
            Route::post('/{id}/cancel', 'cancel_submission')->middleware(['submissionExists', 'submissionBelongsToAuth', 'submissionHasStatus:pending']); // TESTED
        });
    });


    Route::prefix('judge')->middleware(['auth:sanctum', 'hasRole:judge'])
        ->controller(JudgeController::class)->group(function() {
        Route::get('/submissions', 'get_submissions'); // TESTED
        Route::post('/submission/{id}/assign-judge', 'assign_judge')->middleware(['submissionExists', 'submissionHasStatus:pending']); // TESTED
        Route::post('/submission/{id}/judge', 'judge_submission')->middleware(['submissionExists', 'canValidateSubmission', 'submissionHasStatus:judging']); // TESTED
    });
});
Route::get('/hackathon-certificates', function() {
    set_time_limit(800);
    $file = public_path("../database/seeders/hackathon-accepted.csv");
    $records = CSVReader::import_CSV($file);
    foreach($records as $record) {
        $current = file_get_contents(public_path("../database/seeders/sent.txt"));
        Mail::to($record['email'])->send(new HackathonCertificate($record['email']));
        $current .= $record['email']."\n";
        file_put_contents(public_path("../database/seeders/sent.txt"), $current);
    }
    return response()->json([
        'success' => true
    ]);
});
// Route::get('/challenges-acceptance', function() {
//     set_time_limit(800);
//     $file = public_path("../database/seeders/participants.csv");
//     $records = CSVReader::import_CSV($file);
//     foreach($records as $record) {
//         $current = file_get_contents(public_path("../database/seeders/sent.txt"));
//         Mail::to($record['email'])->send(new ChallengesAcceptance());
//         $current .= $record['email']."\n";
//         file_put_contents(public_path("../database/seeders/sent.txt"), $current);
//     }
//     return response()->json([
//         'success' => true
//     ]);
// })->middleware(['auth:sanctum', 'hasRole:admin']);


// Route::get('/hackathon-acceptance', function() {
//     set_time_limit(800);
//     $file = public_path("../database/seeders/hackathon-accepted.csv");
//     $records = CSVReader::import_CSV($file);
//     foreach($records as $record) {
//         $current = file_get_contents(public_path("../database/seeders/sent-acceptence-emails.txt"));
//         Mail::to($record['email'])->send(new HackathonAccepted());
//         $current .= $record['email']."\n";
//         file_put_contents(public_path("../database/seeders/sent-acceptence-emails.txt"), $current);
//     }
//     return response()->json([
//         'success' => true
//     ]);
// })->middleware(['auth:sanctum', 'hasRole:admin']);

// Route::get('/hackathon-waiting', function() {
//     set_time_limit(1200);
//     $file = public_path("../database/seeders/hackathon-waitingList.csv");
//     $records = CSVReader::import_CSV($file);
//     foreach($records as $record) {
//         $current = file_get_contents(public_path("../database/seeders/sent-waitingList.txt"));
//         Mail::to($record['email'])->send(new HackathonRefused());
//         $current .= $record['email']."\n";
//         file_put_contents(public_path("../database/seeders/sent-waitingList.txt"), $current);
//     }
//     return response()->json([
//         'success' => true
//     ]);
// })->middleware(['auth:sanctum', 'hasRole:admin']);

