<?php

namespace Database\Seeders;

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
        for($i=1; $i<=20; $i++) {
            User::create([
                'full_name' => 'participant'.$i,
                'email' => 'participant'.$i.'@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'participant',
                'points' => 0,
            ]);
        }
    }
}
