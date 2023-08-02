<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::create([
        //     'f_name' => 'admin',
        //     'l_name' => 'admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('12345678'),
        //     'job' => 'admin',
        //     'role_id' => 1
        // ]);
    }
}
