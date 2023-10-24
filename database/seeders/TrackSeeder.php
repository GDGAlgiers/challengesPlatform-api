<?php

namespace Database\Seeders;

use App\Models\Track;
use Illuminate\Database\Seeder;
use App\Helpers\CSVReader;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Track::factory()->count(4)->create();
        Track::create([
            'type' => 'Web Development',
            'description' => 'Unleash your creativity in the digital realm! Build the future of the web with innovative design and flawless functionality, a new guest is here! Welcome him'
        ]);
        Track::create([
            'type' => 'Mobile Development',
            'description' => 'Empower the world at your fingertips! Craft cutting-edge mobile apps that redefine convenience and connectivity'
        ]);
        Track::create([
            'type' => 'AI Development',
            'description' => 'Dive into the world of intelligent machines and algorithms. Show us how AI can transform our lives and shape a smarter future '
        ]);
        Track::create([
            'type' => 'Game Development',
            'description' => 'Level up your game-making skills! Design, code, and create immersive experiences that push the boundaries of fun and entertainment'
        ]);
        Track::create([
            'type' => 'Cyber Security',
            'description' => 'Defend the digital frontier! Crack the code, thwart the threats, and bring back the stolen flags!'
        ]);
    }
}
