<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'rand1',
                'email' => 'rand1@gmail.com',
                'password' => Hash::make('11229988'),
                'role' => 'admin',
                'statusCode' => true
            ],
            [
                'name' => 'rand2',
                'email' => 'rand2@gmail.com',
                'password' => Hash::make('11229988'),
                'role' => 'admin',
                'statusCode' => true
            ],
        ];
        User::insert($users);
    }
}
