<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

     public function run()
     {
             Team::create([
                 'name' => 'fivex',
                 'token' => '5RO8TTzmUPg4',
             ]);
             Team::create([
                 'name' => 'asdqwe',
                 'token' => 'FVFHAOEr3FA8',
             ]);
     }
 
}
