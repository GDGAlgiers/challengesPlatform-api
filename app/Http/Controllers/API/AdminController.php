<?php

namespace App\Http\Controllers\API;

use App\Http\Repositories\ChallengeRepository;
use App\Http\Repositories\GeneralRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\TrackRepository;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    private $userRepository;
    private $challengeRepository;
    private $trackRepository;
    private $generalRepository;
    public function __construct(UserRepository $userRepository, ChallengeRepository $challengeRepository, TrackRepository $trackRepository, GeneralRepository $generalRepository) {
        $this->userRepository = $userRepository;
        $this->challengeRepository = $challengeRepository;
        $this->trackRepository = $trackRepository;
        $this->generalRepository = $generalRepository;
    }

    public function get_all_users() {
        $response = $this->userRepository->getAll();
        return $this->sendResponse($response['data'], $response['message']);
    }
    public function create_participant(Request $request) {
        $response = $this->userRepository->create_participant($request);
        if(!$response['success']) return $this->sendError($response['message'], $response['data']);

        return $this->sendResponse($response['data'], $response['message']);
    }

    public function create_judge(Request $request) {
        $response = $this->userRepository->create_judge($request);
        if(!$response['success']) {
            return $this->sendError($response['message'], $response['data']);
        }
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function delete_user($id) {
        $response = $this->userRepository->delete_user($id);
        if(!$response['success']) {
            return $this->sendError($response['message']);
        }
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function get_challenges() {
        $response = $this->challengeRepository->get_all();
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function create_challenges(Request $request) {
        $response = $this->challengeRepository->create($request);
        if(!$response['success']) {
            return $this->sendError($response['message'], $response['data']);
        }
        return $this->sendResponse($response['data'], $response['message'], 201);
    }

    public function update_challenge(Request $request, $id) {
        $response = $this->challengeRepository->update($request, $id);
        if(!$response['success']) return $this->sendError($response['message'], $response['data']);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function lock_challenge($id) {
        $response = $this->challengeRepository->lockById($id);
        if(!$response['success']) return $this->sendError($response['message']);
        return $this->sendResponse($response['data'], $response['message']);
    }
    public function unlock_challenge($id) {
        $response = $this->challengeRepository->unlockById($id);
        if (!$response['success']) return $this->sendError($response['message']);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function delete_challenge($id) {
        $response = $this->challengeRepository->delete($id);
        if(!$response['success']) return $this->sendError($response['message']);

        return $this->sendResponse($response['data'], $response['message']);
    }

    public function get_tracks() {
        $response = $this->trackRepository->get_all();
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function create_track(Request $request) {
        $response = $this->trackRepository->create($request);
        if(!$response['success']) return $this->sendError($response['message'], $response['data']);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function update_track(Request $request, $id) {
        $response = $this->trackRepository->updateById($request, $id);
        if(!$response['success']) return $this->sendError($response['message'], $response['data']);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function lock_tracks() {
        $response = $this->trackRepository->lock();
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function unlock_tracks() {
        $response = $this->trackRepository->unlock();
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function lock_track($id) {
        $response = $this->trackRepository->lockById($id);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function unlock_track($id) {
        $response = $this->trackRepository->unlockById($id);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function delete_track($id) {
        $response = $this->trackRepository->delete($id);
        if(!$response['success']) {return $this->sendError($response['message']);}
        return $this->sendResponse($response['data'], $response['message'], 200);
    }

    public function get_stats() {
        $response = $this->generalRepository->getStats();
        return $this->sendResponse($response['data'], $response['message']);
    }

}
