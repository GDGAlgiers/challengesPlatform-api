<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $users = User::where('role', 'participant')->get();
        // foreach($users as $user) {
        //     $challenges = $user->track->challenges()->where('requires_judge', true)->get()->toArray();
        //     if(count($challenges) > 0) {
        //         $randomChallenge = $challenges[rand(0, count($challenges)-1)];
        //         $track = Track::find($randomChallenge['track_id']);
        //         Submission::create([
        //             'challenge_id' => $randomChallenge['id'],
        //             'participant_id' => $user->id,
        //             'track_id' => $track->id,
        //             'attachment' => 'random attachment',
        //             'status' => 'pending'
        //         ]);
        //     }
        // }
    }
}
