<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

                    $admin = User::create([
                        'name' => 'Admin User',
                        'email' => 'admin@example.com',
                        'password' => Hash::make('Password123!'),
                    ]);

                    $user = User::create([
                        'name' => 'Regular User',
                        'email' => 'user@example.com',
                        'password' => Hash::make('Password123!'),
                    ]);

                    $manager = User::create([
                        'name' => 'Manager User',
                        'email' => 'manager@example.com',
                        'password' => Hash::make('Password123!'),
                    ]);
    }
}
