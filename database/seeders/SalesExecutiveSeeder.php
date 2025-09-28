<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesExecutive;

class SalesExecutiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $executives = [
            [
                'executive_code' => 'SE-001',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@gemshop.com',
                'phone' => '+1-555-0101',
                'address' => '123 Main Street',
                'city' => 'New York',
                'country' => 'USA',
                'date_of_birth' => '1985-03-15',
                'gender' => 'female',
                'national_id' => 'SSN-123-45-6789',
                'hire_date' => '2020-01-15',
                'salary' => 75000.00,
                'department' => 'Sales',
                'position' => 'Senior Sales Executive',
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Top performer with excellent customer relationships.'
            ],
            [
                'executive_code' => 'SE-002',
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'email' => 'michael.chen@gemshop.com',
                'phone' => '+1-555-0102',
                'address' => '456 Oak Avenue',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'date_of_birth' => '1988-07-22',
                'gender' => 'male',
                'national_id' => 'SSN-987-65-4321',
                'hire_date' => '2019-06-01',
                'salary' => 82000.00,
                'department' => 'Sales',
                'position' => 'Sales Manager',
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Expert in luxury jewelry sales and team leadership.'
            ],
            [
                'executive_code' => 'SE-003',
                'first_name' => 'Emily',
                'last_name' => 'Rodriguez',
                'email' => 'emily.rodriguez@gemshop.com',
                'phone' => '+1-555-0103',
                'address' => '789 Pine Street',
                'city' => 'Miami',
                'country' => 'USA',
                'date_of_birth' => '1990-11-08',
                'gender' => 'female',
                'national_id' => 'SSN-456-78-9012',
                'hire_date' => '2021-03-10',
                'salary' => 68000.00,
                'department' => 'Sales',
                'position' => 'Sales Executive',
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Rising star with strong communication skills.'
            ],
            [
                'executive_code' => 'SE-004',
                'first_name' => 'David',
                'last_name' => 'Thompson',
                'email' => 'david.thompson@gemshop.com',
                'phone' => '+1-555-0104',
                'address' => '321 Elm Street',
                'city' => 'Chicago',
                'country' => 'USA',
                'date_of_birth' => '1983-05-12',
                'gender' => 'male',
                'national_id' => 'SSN-789-01-2345',
                'hire_date' => '2018-09-15',
                'salary' => 90000.00,
                'department' => 'Sales',
                'position' => 'Regional Sales Director',
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Experienced leader with proven track record.'
            ],
            [
                'executive_code' => 'SE-005',
                'first_name' => 'Lisa',
                'last_name' => 'Anderson',
                'email' => 'lisa.anderson@gemshop.com',
                'phone' => '+1-555-0105',
                'address' => '654 Maple Drive',
                'city' => 'Seattle',
                'country' => 'USA',
                'date_of_birth' => '1987-12-03',
                'gender' => 'female',
                'national_id' => 'SSN-234-56-7890',
                'hire_date' => '2020-11-20',
                'salary' => 71000.00,
                'department' => 'Sales',
                'position' => 'Sales Executive',
                'employment_status' => 'on_leave',
                'is_active' => false,
                'notes' => 'Currently on maternity leave. Expected return in 3 months.'
            ],
            [
                'executive_code' => 'SE-006',
                'first_name' => 'Robert',
                'last_name' => 'Wilson',
                'email' => 'robert.wilson@gemshop.com',
                'phone' => '+1-555-0106',
                'address' => '987 Cedar Lane',
                'city' => 'Boston',
                'country' => 'USA',
                'date_of_birth' => '1982-09-18',
                'gender' => 'male',
                'national_id' => 'SSN-345-67-8901',
                'hire_date' => '2017-04-05',
                'salary' => 78000.00,
                'department' => 'Sales',
                'position' => 'Senior Sales Executive',
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Specializes in corporate and bulk sales.'
            ],
            [
                'executive_code' => 'SE-007',
                'first_name' => 'Jennifer',
                'last_name' => 'Martinez',
                'email' => 'jennifer.martinez@gemshop.com',
                'phone' => '+1-555-0107',
                'address' => '147 Birch Street',
                'city' => 'Phoenix',
                'country' => 'USA',
                'date_of_birth' => '1991-02-28',
                'gender' => 'female',
                'national_id' => 'SSN-567-89-0123',
                'hire_date' => '2022-01-10',
                'salary' => 65000.00,
                'department' => 'Sales',
                'position' => 'Junior Sales Executive',
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'New hire showing great potential in customer service.'
            ],
            [
                'executive_code' => 'SE-008',
                'first_name' => 'James',
                'last_name' => 'Brown',
                'email' => 'james.brown@gemshop.com',
                'phone' => '+1-555-0108',
                'address' => '258 Spruce Avenue',
                'city' => 'Denver',
                'country' => 'USA',
                'date_of_birth' => '1986-08-14',
                'gender' => 'male',
                'national_id' => 'SSN-678-90-1234',
                'hire_date' => '2019-08-12',
                'salary' => 73000.00,
                'department' => 'Sales',
                'position' => 'Sales Executive',
                'employment_status' => 'active',
                'is_active' => true,
                'notes' => 'Excellent at building long-term client relationships.'
            ]
        ];

        foreach ($executives as $executive) {
            SalesExecutive::create($executive);
        }
    }
}