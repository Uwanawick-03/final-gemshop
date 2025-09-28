<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Craftsman;

class CraftsmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $craftsmen = [
            [
                'craftsman_code' => 'CR-001',
                'first_name' => 'Ahmed',
                'last_name' => 'Hassan',
                'email' => 'ahmed.hassan@gemshop.com',
                'phone' => '+1-555-0201',
                'address' => '123 Jewelry Street',
                'city' => 'New York',
                'country' => 'USA',
                'date_of_birth' => '1980-05-15',
                'gender' => 'male',
                'national_id' => 'ID-123-45-6789',
                'joined_date' => '2018-01-15',
                'primary_skill' => 'Gold Smithing',
                'skills' => ['Gold Smithing', 'Engraving', 'Polishing'],
                'hourly_rate' => 45.00,
                'commission_rate' => 15.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Master goldsmith with 15+ years experience in fine jewelry.'
            ],
            [
                'craftsman_code' => 'CR-002',
                'first_name' => 'Maria',
                'last_name' => 'Rodriguez',
                'email' => 'maria.rodriguez@gemshop.com',
                'phone' => '+1-555-0202',
                'address' => '456 Diamond Avenue',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'date_of_birth' => '1985-08-22',
                'gender' => 'female',
                'national_id' => 'ID-987-65-4321',
                'joined_date' => '2019-06-01',
                'primary_skill' => 'Diamond Setting',
                'skills' => ['Diamond Setting', 'Prong Setting', 'Bezel Setting'],
                'hourly_rate' => 50.00,
                'commission_rate' => 12.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Expert in diamond setting and precious stone work.'
            ],
            [
                'craftsman_code' => 'CR-003',
                'first_name' => 'James',
                'last_name' => 'Thompson',
                'email' => 'james.thompson@gemshop.com',
                'phone' => '+1-555-0203',
                'address' => '789 Craft Lane',
                'city' => 'Chicago',
                'country' => 'USA',
                'date_of_birth' => '1978-12-10',
                'gender' => 'male',
                'national_id' => 'ID-456-78-9012',
                'joined_date' => '2020-03-10',
                'primary_skill' => 'Engraving',
                'skills' => ['Engraving', 'Hand Engraving', 'Machine Engraving'],
                'hourly_rate' => 40.00,
                'commission_rate' => 10.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Specialized in custom engraving and personalization.'
            ],
            [
                'craftsman_code' => 'CR-004',
                'first_name' => 'Sarah',
                'last_name' => 'Wilson',
                'email' => 'sarah.wilson@gemshop.com',
                'phone' => '+1-555-0204',
                'address' => '321 Silver Street',
                'city' => 'Miami',
                'country' => 'USA',
                'date_of_birth' => '1983-03-08',
                'gender' => 'female',
                'national_id' => 'ID-789-01-2345',
                'joined_date' => '2021-09-15',
                'primary_skill' => 'Polishing',
                'skills' => ['Polishing', 'Buffing', 'Finishing'],
                'hourly_rate' => 35.00,
                'commission_rate' => 8.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Expert in jewelry finishing and polishing techniques.'
            ],
            [
                'craftsman_code' => 'CR-005',
                'first_name' => 'David',
                'last_name' => 'Chen',
                'email' => 'david.chen@gemshop.com',
                'phone' => '+1-555-0205',
                'address' => '654 Gem Road',
                'city' => 'Seattle',
                'country' => 'USA',
                'date_of_birth' => '1990-11-25',
                'gender' => 'male',
                'national_id' => 'ID-234-56-7890',
                'joined_date' => '2022-01-20',
                'primary_skill' => 'Repair',
                'skills' => ['Repair', 'Resizing', 'Stone Replacement'],
                'hourly_rate' => 38.00,
                'commission_rate' => 10.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Skilled in jewelry repair and restoration work.'
            ],
            [
                'craftsman_code' => 'CR-006',
                'first_name' => 'Lisa',
                'last_name' => 'Anderson',
                'email' => 'lisa.anderson@gemshop.com',
                'phone' => '+1-555-0206',
                'address' => '987 Craft Street',
                'city' => 'Boston',
                'country' => 'USA',
                'date_of_birth' => '1987-07-18',
                'gender' => 'female',
                'national_id' => 'ID-345-67-8901',
                'joined_date' => '2019-08-12',
                'primary_skill' => 'Chain Making',
                'skills' => ['Chain Making', 'Link Assembly', 'Wire Work'],
                'hourly_rate' => 42.00,
                'commission_rate' => 12.00,
                'employment_status' => 'on_leave',
                'is_active' => false,
                'notes' => 'Currently on maternity leave. Expected return in 2 months.'
            ],
            [
                'craftsman_code' => 'CR-007',
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@gemshop.com',
                'phone' => '+1-555-0207',
                'address' => '147 Workshop Avenue',
                'city' => 'Phoenix',
                'country' => 'USA',
                'date_of_birth' => '1982-04-30',
                'gender' => 'male',
                'national_id' => 'ID-567-89-0123',
                'joined_date' => '2017-11-05',
                'primary_skill' => 'Custom Design',
                'skills' => ['Custom Design', 'Wax Carving', 'Model Making'],
                'hourly_rate' => 55.00,
                'commission_rate' => 15.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Creative designer specializing in custom jewelry pieces.'
            ],
            [
                'craftsman_code' => 'CR-008',
                'first_name' => 'Jennifer',
                'last_name' => 'Martinez',
                'email' => 'jennifer.martinez@gemshop.com',
                'phone' => '+1-555-0208',
                'address' => '258 Artisan Lane',
                'city' => 'Denver',
                'country' => 'USA',
                'date_of_birth' => '1989-09-14',
                'gender' => 'female',
                'national_id' => 'ID-678-90-1234',
                'joined_date' => '2020-12-01',
                'primary_skill' => 'Stone Setting',
                'skills' => ['Stone Setting', 'Pave Setting', 'Channel Setting'],
                'hourly_rate' => 48.00,
                'commission_rate' => 13.00,
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Expert in various stone setting techniques and methods.'
            ]
        ];

        foreach ($craftsmen as $craftsman) {
            Craftsman::create($craftsman);
        }
    }
}
