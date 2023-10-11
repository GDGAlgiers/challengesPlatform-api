<?php

namespace App\Http\Controllers\API;

use App\Http\Repositories\TrackRepository;

class GeneralController extends BaseController
{
    private $trackRepository;

    public function __construct(TrackRepository $trackRepository) {
        $this->trackRepository = $trackRepository;
    }

    public function get_track_types() {
        $response = $this->trackRepository->getTrackTypes();
        return $this->sendResponse($response["data"], $response['message']);
    }

}
