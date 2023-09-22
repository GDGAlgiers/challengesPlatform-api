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
        Track::factory()->create();
    }
}
