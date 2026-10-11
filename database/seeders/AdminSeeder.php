<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'first_name' => 'مدیر',
                'last_name' => 'سیستم',
                'code' => 'ADMIN-001',
                'password' => 'password',
                'role' => 'admin',
            ]
        );
    }
}
