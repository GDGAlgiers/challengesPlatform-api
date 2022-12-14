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
        $file = public_path("../database/seeders/participants.csv");
        $records = CSVReader::import_CSV($file);
        foreach($records as $record) {
            $current = file_get_contents(public_path("../database/seeders/sent.txt"));
            $randomPassword = 'devfest22'.Str::random(5).'@algiers'.Str::random(12). 'challenges';
            $trackID = Track::where('type', $record['track'])->pluck('id')->first();
            $user = User::create([
                'full_name' => $record['fullName'],
                'email' => $record['email'],
                'password' => Hash::make($randomPassword),
                'role' => 'participant',
                'points' => 0,
                'track_id' => $trackID,

            ]);
            Mail::to($record['email'])->send(new ParticipantAccountCreated($user->email, $randomPassword, $record['track']));
            $current .= $record['email']."\n";
            file_put_contents(public_path("../database/seeders/sent.txt"), $current);
        }
        // $tracks = Track::all()->pluck('id')->toArray();
        // for($i=1; $i<=20; $i++) {
        //     User::create([
        //         'full_name' => 'participant'.$i,
        //         'email' => 'participant'.$i.'@gmail.com',
        //         'password' => Hash::make('123456'),
        //         'role' => 'participant',
        //         'points' => 0,
        //         'track_id' => $tracks[rand(0, count($tracks)-1)]
        //     ]);
        // }

        // for($i=1; $i<=4; $i++) {
        //     User::create([
        //         'full_name' => 'judge'.$i,
        //         'email' => 'judge'.$i.'@judge.com',
        //         'password' => Hash::make('123456'),
        //         'role' => 'judge',
        //         'track_id' => $tracks[rand(0, count($tracks)-1)]
        //     ]);
        // }
    }
}
