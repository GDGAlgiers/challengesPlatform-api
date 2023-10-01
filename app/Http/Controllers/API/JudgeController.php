<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\SubmissionRepository;
use Illuminate\Http\Request;

class JudgeController extends BaseController
{
    private $submissionRepository;

    public function __construct(SubmissionRepository $submissionRepository)
    {
        $this->submissionRepository = $submissionRepository;
    }

    public function get_submissions() {
        $response = $this->submissionRepository->getByTrack();
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function assign_judge($id) {
        $response = $this->submissionRepository->assignById($id);
        if(!$response['success']) return $this->sendError($response['message'], null, $response['code']);
        return $this->sendResponse($response['data'], $response['message']);
    }

    public function judge_submission(Request $request, $id) {
        $response = $this->submissionRepository->judgeById($request, $id);
        if(!$response['success']) return $this->sendError($response['message'], $response['data']);
        return $this->sendResponse($response['data'], $response['message']);
    }
}
