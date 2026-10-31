<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@foundit.com'],
            [
                'name' => 'FoundIt Admin',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
            ]
        );
    }
}
