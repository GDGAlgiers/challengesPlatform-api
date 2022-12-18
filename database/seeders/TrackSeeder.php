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
        Track::create([
            'type' => 'Welcome Day22 Challenges',
            'description' => 'Can you earn the golden Ticket and joing GDG Algiers Directly?',
            'is_locked' => 1
        ]);
    }
}
