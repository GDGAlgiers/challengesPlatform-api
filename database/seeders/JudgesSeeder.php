<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;
use App\Helpers\CSVReader;
use Illuminate\Support\Str;
use App\Models\Track;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\JudgeAccountCreated;
class JudgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = public_path("../database/seeders/judges.csv");
        $records = CSVReader::import_CSV($file);
        foreach($records as $record) {
            $randomPassword = 'devfest22'.Str::random(5).'@algiers'.Str::random(12). 'challenges';
            $trackID = Track::where('type', $record['track'])->pluck('id')->first();
            $user = User::create([
                'full_name' => $record['fullName'],
                'email' => $record['email'],
                'password' => Hash::make($randomPassword),
                'role' => 'judge',
                'track_id' => $trackID,

            ]);
            Mail::to($record['email'])->send(new JudgeAccountCreated($user->email, $randomPassword));
        }
    }
}
