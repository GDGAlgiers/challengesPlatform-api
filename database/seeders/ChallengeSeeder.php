<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\Track;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trackIDs = Track::all()->pluck('id')->toArray();
        for($i=1; $i<=30; $i++) {
            Challenge::create([
                'track_id' => $trackIDs[rand(0, count($trackIDs)-1)],
                'name' => 'challenge'.$i,
                'difficulty' => 'easy',
                'description' => 'description'.$i,
                'points' => $i*20,
                'max_tries' => 2,
                'requires_judge' => true,

            ]);
        }

        Challenge::create([
            'track_id' => $trackIDs[rand(0, count($trackIDs)-1)],
            'name' => 'challenge31',
            'difficulty' => 'easy',
            'description' => 'description'.$i,
            'external_resource' => 'https://devfest22.gdgalgiers.com',
            'points' => 250,
            'max_tries' => 2,
            'requires_judge' => false,
            'solution' => 'solution'
        ]);

        // $challengeIDs = Challenge::all()->pluck('id')->toArray();
        // $participants = User::where('role', 'participant')->pluck('id')->toArray();
        // for($i=1; $i<=20; $i++) {
        //     DB::table('submissions')->insert([
        //         'challenge_id' => $challengeIDs[rand(0, count($challengeIDs)-1)],
        //         'participant_id' => $participants[rand(0, count($participants)-1)]
        //     ]);
        // }
    }
}
