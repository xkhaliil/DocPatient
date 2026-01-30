<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cabinet;

class DoctorsAndCabinetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create doctor users
        $doctors = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'password' => bcrypt('password'),
                'role' => 'doctor'
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael.chen@example.com',
                'password' => bcrypt('password'),
                'role' => 'doctor'
            ],
            [
                'name' => 'Dr. Emily Rodriguez',
                'email' => 'emily.rodriguez@example.com',
                'password' => bcrypt('password'),
                'role' => 'doctor'
            ],
            [
                'name' => 'Dr. James Wilson',
                'email' => 'james.wilson@example.com',
                'password' => bcrypt('password'),
                'role' => 'doctor'
            ],
            [
                'name' => 'Dr. Lisa Thompson',
                'email' => 'lisa.thompson@example.com',
                'password' => bcrypt('password'),
                'role' => 'doctor'
            ]
        ];

        foreach ($doctors as $doctorData) {
            $doctor = User::create($doctorData);
            
            // Create cabinet for each doctor
            $cabinets = [
                [
                    'name' => 'Cardiology Clinic',
                    'location' => '123 Medical Center Drive, Suite 100, New York, NY 10001',
                    'doctor_id' => $doctor->id
                ],
                [
                    'name' => 'Family Health Center',
                    'location' => '456 Wellness Avenue, Los Angeles, CA 90001',
                    'doctor_id' => $doctor->id
                ],
                [
                    'name' => 'Specialty Medical Group',
                    'location' => '789 Healthcare Boulevard, Chicago, IL 60601',
                    'doctor_id' => $doctor->id
                ]
            ];

            // Create one random cabinet for each doctor
            $randomCabinet = $cabinets[array_rand($cabinets)];
            Cabinet::create($randomCabinet);
        }
    }
}