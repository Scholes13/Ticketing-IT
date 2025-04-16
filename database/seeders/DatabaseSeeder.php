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
    }
}
