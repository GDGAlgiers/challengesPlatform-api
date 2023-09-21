<?php

namespace Database\Seeders;

use App\Helpers\CSVReader;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ParticipantAccountCreated;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $track = Track::where('type', 'Flutter Forward Challenges')->first()->pluck('id');
        for($i=1; $i<=15; $i++) {
            User::create([
                'full_name' => 'username'.$i,
                'password' => Hash::make('123456789'),
                'points' => 0,
                'role' => 'participant',
                'ip' => '127.0.0.1',
                'track_id' => $track
            ]);
        }
    }
}
