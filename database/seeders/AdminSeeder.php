<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!DB::table('admins')->where('email', 'admin@jpelatihan.com')->exists()) {
            DB::table('admins')->insert([
                'name' => 'Super Admin',
                'email' => 'admin@jpelatihan.com',
                'password' => Hash::make('admin123'),
            ]);
        }
    }
}

