<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ChallengeRepository;
use App\Http\Repositories\SubmissionRepository;
use App\Http\Repositories\TrackRepository;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParticipantController extends BaseController
{
    private $challengeRepository;
    private $trackRepository;
    private $submissionRepository;

    public function __construct(ChallengeRepository $challengeRepository, TrackRepository $trackRepository, SubmissionRepository $submissionRepository) {
        $this->challengeRepository = $challengeRepository;
        $this->trackRepository = $trackRepository;
        $this->submissionRepository = $submissionRepository;
    }

    public function get_tracks() {
        $response = $this->trackRepository->get_all();

        return $this->sendResponse($response['data'], $response['message']);
    }

    public function get_track_challenges($type) {
        $response = $this->trackRepository->get_track_challenges($type);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function submit_challenge(Request $request, $id) {
        $response = $this->challengeRepository->submit($request, $id);
        if(!$response['success']) return $this->sendError($response['message'], $response['data']);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function get_submissions($id) {
        $response = $this->challengeRepository->getSubmissionsById($id);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function leaderboard($type) {
        $response = $this->trackRepository->getLeaderboardByName($type);
        if(!$response['success']) return $this->sendError($response['message']);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function cancel_submission($id) {
        $response = $this->submissionRepository->cancelById($id);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function download_attachment($id) {
        $challenge = Challenge::find($id);

        if(!$challenge->attachment) return response()->json([
            'success' => false,
            'message' => 'This challenge does not have an attachment'
        ], 404);

        $headers = ['Content-Type: application/octet-stream'];
        if(Storage::exists($challenge->attachment)) {
            return Storage::download($challenge->attachment, $challenge->name, $headers);
        }

        return response()->json([
            'success' => false,
            'message' => 'Can not find the challenge file, contact the admins'
        ], 404);
    }

    public function get_all_submissions() {
        $response = $this->submissionRepository->getAll();
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function get_challenge($id) {
        $response = $this->challengeRepository->getById($id);
        if(!$response['success']) return $this->sendError($response['message']);
        return $this->sendResponse($response['data'], $response['message']);
    }
}
