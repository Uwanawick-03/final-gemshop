<?php

namespace Database\Seeders;

use App\Models\TourGuide;
use Illuminate\Database\Seeder;

class TourGuideSeeder extends Seeder
{
    public function run(): void
    {
        $tourGuides = [
            [
                'guide_code' => 'TG-001',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '+1-555-0101',
                'address' => '123 Main Street',
                'city' => 'New York',
                'country' => 'USA',
                'gender' => 'female',
                'joined_date' => '2023-01-15',
                'languages' => ['English', 'Spanish', 'French'],
                'service_areas' => ['Downtown', 'Central Park', 'Brooklyn'],
                'license_number' => 'TG-NY-001',
                'license_expiry' => '2025-12-31',
                'daily_rate' => 150.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Experienced city tour guide with excellent customer service.'
            ],
            [
                'guide_code' => 'TG-002',
                'first_name' => 'Ahmed',
                'last_name' => 'Hassan',
                'email' => 'ahmed.hassan@example.com',
                'phone' => '+1-555-0102',
                'address' => '456 Oak Avenue',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'gender' => 'male',
                'joined_date' => '2023-03-20',
                'languages' => ['English', 'Arabic', 'French'],
                'service_areas' => ['Hollywood', 'Beverly Hills', 'Santa Monica'],
                'license_number' => 'TG-CA-002',
                'license_expiry' => '2025-08-15',
                'daily_rate' => 180.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Specializes in celebrity tours and historical landmarks.'
            ],
            [
                'guide_code' => 'TG-003',
                'first_name' => 'Maria',
                'last_name' => 'Rodriguez',
                'email' => 'maria.rodriguez@example.com',
                'phone' => '+1-555-0103',
                'address' => '789 Pine Street',
                'city' => 'Miami',
                'country' => 'USA',
                'gender' => 'female',
                'joined_date' => '2023-06-10',
                'languages' => ['English', 'Spanish', 'Portuguese'],
                'service_areas' => ['South Beach', 'Little Havana', 'Wynwood'],
                'license_number' => 'TG-FL-003',
                'license_expiry' => '2026-03-20',
                'daily_rate' => 140.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Bilingual guide with expertise in Cuban culture and art.'
            ],
            [
                'guide_code' => 'TG-004',
                'first_name' => 'David',
                'last_name' => 'Chen',
                'email' => 'david.chen@example.com',
                'phone' => '+1-555-0104',
                'address' => '321 Elm Street',
                'city' => 'San Francisco',
                'country' => 'USA',
                'gender' => 'male',
                'joined_date' => '2023-09-05',
                'languages' => ['English', 'Mandarin', 'Japanese'],
                'service_areas' => ['Chinatown', 'Golden Gate', 'Fisherman\'s Wharf'],
                'license_number' => 'TG-CA-004',
                'license_expiry' => '2025-11-30',
                'daily_rate' => 160.00,
                'employment_status' => 'on_leave',
                'is_active' => true,
                'notes' => 'Currently on family leave. Returns in March 2024.'
            ],
            [
                'guide_code' => 'TG-005',
                'first_name' => 'Emma',
                'last_name' => 'Williams',
                'email' => 'emma.williams@example.com',
                'phone' => '+1-555-0105',
                'address' => '654 Maple Drive',
                'city' => 'Boston',
                'country' => 'USA',
                'gender' => 'female',
                'joined_date' => '2022-11-12',
                'languages' => ['English', 'Italian'],
                'service_areas' => ['Downtown', 'Harvard Square', 'Fenway'],
                'license_number' => 'TG-MA-005',
                'license_expiry' => '2025-07-10',
                'daily_rate' => 170.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'History major with deep knowledge of Boston\'s revolutionary past.'
            ]
        ];

        foreach ($tourGuides as $guideData) {
            TourGuide::create($guideData);
        }
    }
}
