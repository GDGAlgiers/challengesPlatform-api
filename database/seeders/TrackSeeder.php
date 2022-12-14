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
        $file = public_path("../database/seeders/tracks.csv");
        $records = CSVReader::import_CSV($file);
        foreach($records as $record) {
            Track::create([
                'type' => $record['type'],
                'is_locked' => true,
                'description' => $record['description'],
            ]);
        }
    }
}
