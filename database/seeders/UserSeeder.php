<?php

namespace Database\Seeders;

use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'full_name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);
        $tracks = Track::all()->pluck('id')->toArray();
        for($i=1; $i<=20; $i++) {
            User::create([
                'full_name' => 'participant'.$i,
                'email' => 'participant'.$i.'@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'participant',
                'points' => 0,
                'track_id' => $tracks[rand(0, count($tracks)-1)]
            ]);
        }

        for($i=1; $i<=4; $i++) {
            User::create([
                'full_name' => 'judge'.$i,
                'email' => 'judge'.$i.'@judge.com',
                'password' => Hash::make('123456'),
                'role' => 'judge',
                'track_id' => $tracks[rand(0, count($tracks)-1)]
            ]);
        }
    }
}
