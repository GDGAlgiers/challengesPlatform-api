<?php

namespace Database\Seeders;

use App\Models\Track;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Track::create([
            'type' => 'web',
            'is_locked' => true,
            'description' => 'trackWEB',
        ]);
        Track::create([
            'type' => 'ai',
            'is_locked' => true,
            'description' => 'trackAI',
        ]);
        Track::create([
            'type' => 'mobile',
            'is_locked' => true,
            'description' => 'trackMOBILE',
        ]);
        Track::create([
            'type' => 'cyberSecurity',
            'is_locked' => true,
            'description' => 'trackSEC',
        ]);
        Track::create([
            'type' => 'others',
            'is_locked' => true,
            'description' => 'trackOTHERS',
        ]);
    }
}
