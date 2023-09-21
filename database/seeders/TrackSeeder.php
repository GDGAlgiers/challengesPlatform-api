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
                'type' => 'Flutter Forward Challenges',
                'is_locked' => true,
                'description' => 'Flutter Forward Challenges description',
            ]);
    }
}
