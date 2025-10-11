<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Citizen;
use App\Models\User;

class CitizenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
               // Get user IDs for different roles
        $localLeaderIds = User::where('role', 'local_leader')->pluck('id')->toArray();
        $citizenUserIds = User::where('role', 'citizen')->pluck('id')->toArray();

        $citizens = [
            // Verified citizens
            [
                'user_id' => $citizenUserIds[0], // John Smith
                'national_id' => '7501011234081',
                'full_name' => 'John Smith',
                'date_of_birth' => '1975-01-01',
                'address' => '123 Main Street, Springfield, IL 62704',
                'phone_number' => '+1-555-0101',
                'verification_status' => 'verified',
                'verification_notes' => 'Documents verified successfully',
                'verified_by' => $localLeaderIds[0],
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(25),
            ],
            [
                'user_id' => $citizenUserIds[1], // Emma Davis
                'national_id' => '8502151234082',
                'full_name' => 'Emma Davis',
                'date_of_birth' => '1985-02-15',
                'address' => '456 Oak Avenue, Rivertown, CA 90210',
                'phone_number' => '+1-555-0102',
                'verification_status' => 'verified',
                'verification_notes' => 'All documents in order',
                'verified_by' => $localLeaderIds[1],
                'created_at' => now()->subDays(28),
                'updated_at' => now()->subDays(22),
            ],
            [
                'user_id' => $citizenUserIds[2], // Alex Johnson
                'national_id' => '9205301234083',
                'full_name' => 'Alex Johnson',
                'date_of_birth' => '1992-05-30',
                'address' => '789 Pine Road, Mountainview, TX 75001',
                'phone_number' => '+1-555-0103',
                'verification_status' => 'verified',
                'verification_notes' => 'Identity confirmed',
                'verified_by' => $localLeaderIds[2],
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(20),
            ],

            // Pending verification
            [
                'user_id' => $citizenUserIds[3], // Sophia Williams
                'national_id' => '8807121234084',
                'full_name' => 'Sophia Williams',
                'date_of_birth' => '1988-07-12',
                'address' => '321 Elm Street, Lakeside, FL 33101',
                'phone_number' => '+1-555-0104',
                'verification_status' => 'pending',
                'verification_notes' => null,
                'verified_by' => null,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'user_id' => $citizenUserIds[4], // Daniel Brown
                'national_id' => '7911241234085',
                'full_name' => 'Daniel Brown',
                'date_of_birth' => '1979-11-24',
                'address' => '654 Cedar Lane, Hilltop, NY 10001',
                'phone_number' => '+1-555-0105',
                'verification_status' => 'pending',
                'verification_notes' => null,
                'verified_by' => null,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id' => $citizenUserIds[5], // Olivia Martinez
                'national_id' => '9303081234086',
                'full_name' => 'Olivia Martinez',
                'date_of_birth' => '1993-03-08',
                'address' => '987 Birch Court, Seaside, CA 90401',
                'phone_number' => '+1-555-0106',
                'verification_status' => 'pending',
                'verification_notes' => 'Waiting for address proof',
                'verified_by' => null,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],

            // Rejected applications
            [
                'user_id' => $citizenUserIds[6], // William Taylor
                'national_id' => '8612091234087',
                'full_name' => 'William Taylor',
                'date_of_birth' => '1986-12-09',
                'address' => '147 Maple Drive, Valleytown, AZ 85001',
                'phone_number' => '+1-555-0107',
                'verification_status' => 'rejected',
                'verification_notes' => 'Incomplete documentation provided',
                'verified_by' => $localLeaderIds[0],
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(18),
            ],
            [
                'user_id' => $citizenUserIds[7], // Ava Anderson
                'national_id' => '9104051234088',
                'full_name' => 'Ava Anderson',
                'date_of_birth' => '1991-04-05',
                'address' => '258 Walnut Street, Northwood, WA 98101',
                'phone_number' => '+1-555-0108',
                'verification_status' => 'rejected',
                'verification_notes' => 'National ID does not match provided documents',
                'verified_by' => $localLeaderIds[1],
                'created_at' => now()->subDays(17),
                'updated_at' => now()->subDays(15),
            ],

            // More citizens for testing
            [
                'user_id' => $citizenUserIds[8], // Liam Wilson
                'national_id' => '8706181234089',
                'full_name' => 'Liam Wilson',
                'date_of_birth' => '1987-06-18',
                'address' => '369 Spruce Avenue, Eastside, CO 80201',
                'phone_number' => '+1-555-0109',
                'verification_status' => 'verified',
                'verification_notes' => 'Quick verification process',
                'verified_by' => $localLeaderIds[2],
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id' => $citizenUserIds[9], // Mia Garcia
                'national_id' => '9409111234090',
                'full_name' => 'Mia Garcia',
                'date_of_birth' => '1994-09-11',
                'address' => '741 Oakwood Drive, Westend, GA 30301',
                'phone_number' => '+1-555-0110',
                'verification_status' => 'pending',
                'verification_notes' => 'New application, under review',
                'verified_by' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ];

        foreach ($citizens as $citizen) {
            Citizen::create($citizen);
        }

        $this->command->info('Citizens seeded successfully!');
        $this->command->info('Total users created: ' . User::count());
        $this->command->info('Total citizens created: ' . Citizen::count());
        
        // Display verification statistics
        $verified = Citizen::where('verification_status', 'verified')->count();
        $pending = Citizen::where('verification_status', 'pending')->count();
        $rejected = Citizen::where('verification_status', 'rejected')->count();
        
        $this->command->info("Verification Stats:");
        $this->command->info("Verified: {$verified}");
        $this->command->info("Pending: {$pending}");
        $this->command->info("Rejected: {$rejected}");
    }
}
