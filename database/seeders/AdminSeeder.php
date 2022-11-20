<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i<=3; $i++) {
            User::create([
                'full_name' => 'admin'.$i,
                'email' => 'admin'.$i.'@admin.com',
                'password' => Hash::make('123456'),
                'role' => 'admin'
            ]);
        }
    }
}
