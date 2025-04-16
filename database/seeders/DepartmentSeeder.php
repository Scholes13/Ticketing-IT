<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'IT Support',
                'description' => 'Provides technical support and maintenance for all IT systems',
                'is_active' => true,
            ],
            [
                'name' => 'HR Department',
                'description' => 'Handles employee relations, recruitment and personnel matters',
                'is_active' => true,
            ],
            [
                'name' => 'Finance',
                'description' => 'Manages company financial operations and accounting',
                'is_active' => true,
            ],
            [
                'name' => 'Operations',
                'description' => 'Oversees day-to-day business operations and logistics',
                'is_active' => true,
            ],
            [
                'name' => 'Customer Service',
                'description' => 'Handles customer inquiries, complaints and feedback',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
} 