<?php
namespace App\Http\Repositories;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\Track;
use Illuminate\Database\Eloquent\Builder;

Class GeneralRepository {

    public function getStats() {
        $response = [];
        $data = [];
        $tracks = Track::all();
        $data['total_challenges'] = count(Challenge::all());
        $data['tracks_stats'] = [
            'total_tracks' => count($tracks)
        ];
        foreach($tracks as $track) {
            $data['tracks_stats'][$track->type] = $track->participants()->count();
        }
        $data['total_submissions'] = count(Submission::all());
        $response['success'] = true;
        $response['data'] = $data;
        $response['message'] = 'Successfully retrieved statistics';

        return $response;
    }

}
