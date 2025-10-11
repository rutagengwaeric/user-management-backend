<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
               $users = [
            // System Administrators
            [
                'name' => 'Admin User',
                'email' => 'admin@system.com',
                'password' => Hash::make('password123'),
                'role' => 'system_admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@system.com',
                'password' => Hash::make('password123'),
                'role' => 'system_admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Local Leaders
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@local.gov',
                'password' => Hash::make('password123'),
                'role' => 'local_leader',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maria Rodriguez',
                'email' => 'maria.rodriguez@local.gov',
                'password' => Hash::make('password123'),
                'role' => 'local_leader',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'David Kim',
                'email' => 'david.kim@local.gov',
                'password' => Hash::make('password123'),
                'role' => 'local_leader',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Policy Makers
            [
                'name' => 'Dr. James Wilson',
                'email' => 'james.wilson@policy.org',
                'password' => Hash::make('password123'),
                'role' => 'policy_maker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fatima Al-Mansoori',
                'email' => 'fatima.almansoori@policy.org',
                'password' => Hash::make('password123'),
                'role' => 'policy_maker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Robert Thompson',
                'email' => 'robert.thompson@policy.org',
                'password' => Hash::make('password123'),
                'role' => 'policy_maker',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Citizens
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma.davis@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alex Johnson',
                'email' => 'alex.johnson@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sophia Williams',
                'email' => 'sophia.williams@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Daniel Brown',
                'email' => 'daniel.brown@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Olivia Martinez',
                'email' => 'olivia.martinez@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'William Taylor',
                'email' => 'william.taylor@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ava Anderson',
                'email' => 'ava.anderson@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Liam Wilson',
                'email' => 'liam.wilson@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mia Garcia',
                'email' => 'mia.garcia@email.com',
                'password' => Hash::make('password123'),
                'role' => 'citizen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('Users seeded successfully!');

    }
}
