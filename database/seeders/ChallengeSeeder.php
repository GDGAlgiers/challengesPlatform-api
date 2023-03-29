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
        $track= Track::where('type', 'Flutter Forward Challenges')->first()->pluck('id');


        for($i=0; $i<= 12; $i++) {
            Challenge::create([
                'track_id' => $track,
                'name' => 'challenge'.$i,
                'difficulty' => 'easy',
                'description' => 'description'.$i,
                'external_resource' => '',
                'points' => 250,
                'max_tries' => 2,
                'requires_judge' => true,
            ]);
        }

    }
}
