<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'Administration',
            'email_verified_at' => now(),
        ]);

        // Create Advisor Users
        User::create([
            'name' => 'Dr. Somchai Advisor',
            'email' => 'advisor1@example.com',
            'password' => Hash::make('password'),
            'role' => 'advisor',
            'department' => 'Computer Engineering',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Dr. Siriwan Advisor',
            'email' => 'advisor2@example.com',
            'password' => Hash::make('password'),
            'role' => 'advisor',
            'department' => 'Software Engineering',
            'email_verified_at' => now(),
        ]);

        // Create Student Users
        User::create([
            'name' => 'Nattapong Student',
            'email' => 'student1@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'department' => 'Computer Engineering',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pimchanok Student',
            'email' => 'student2@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'department' => 'Software Engineering',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Thanawat Student',
            'email' => 'student3@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'department' => 'Computer Engineering',
            'email_verified_at' => now(),
        ]);

        // Create Committee Members
        User::create([
            'name' => 'Prof. Wichai Committee',
            'email' => 'committee1@example.com',
            'password' => Hash::make('password'),
            'role' => 'committee',
            'department' => 'Computer Engineering',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Assoc. Prof. Kannika Committee',
            'email' => 'committee2@example.com',
            'password' => Hash::make('password'),
            'role' => 'committee',
            'department' => 'Software Engineering',
            'email_verified_at' => now(),
        ]);
    }
}
