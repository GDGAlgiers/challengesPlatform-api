<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ChallengeRepository;
use App\Http\Repositories\TrackRepository;
use Illuminate\Http\Request;

class ParticipantController extends BaseController
{
    private $challengeRepository;
    private $trackRepository;

    public function __construct(ChallengeRepository $challengeRepository, TrackRepository $trackRepository) {
        $this->challengeRepository = $challengeRepository;
        $this->trackRepository = $trackRepository;
    }

    public function get_tracks() {
        $response = $this->trackRepository->get_all();

        return $this->sendResponse($response['data'], $response['message']);
    }

    public function get_track_challenges($id) {
        $response = $this->trackRepository->get_track_challenges($id);
        if(!$response['success']) return $this->sendError($response['message']);

        return $this->sendResponse($response['data'], $response['message']);
    }

    public function submit_challenge(Request $request, $id) {
        $response = $this->challengeRepository->submit($request, $id);
    }
}
