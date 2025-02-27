<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Toyeight Super Admin',
            'email' => 'admin@example.org',
            'password' => bcrypt('secret'),
        ]);
    }
}
