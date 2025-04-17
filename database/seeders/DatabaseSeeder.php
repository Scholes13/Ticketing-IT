<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the user seeder to create admin user and staff
        $this->call(UserSeeder::class);
        
        // Call the category seeder to create ticket categories
        $this->call(CategorySeeder::class);
        
        // Call the department seeder to create departments
        $this->call(DepartmentSeeder::class);
        
        // Create default staff member for ticket assignment
        \App\Models\Staff::firstOrCreate(
            ['email' => 'pramuji.arif@example.com'],
            [
                'name' => 'Pramuji Arif Yulianto',
                'position' => 'Support Specialist',
                'gender' => 'Male',
                'phone' => '08123456789',
                'department' => 'BAS',
                'is_active' => true,
            ]
        );
    }
}
