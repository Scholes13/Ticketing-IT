<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hardware Issue',
                'description' => 'Problems with physical computer equipment',
                'color' => '#dc3545', // red
            ],
            [
                'name' => 'Software Issue',
                'description' => 'Problems with applications or operating system',
                'color' => '#fd7e14', // orange
            ],
            [
                'name' => 'Network Issue',
                'description' => 'Problems with internet or local network connectivity',
                'color' => '#0d6efd', // blue
            ],
            [
                'name' => 'Account Access',
                'description' => 'Problems with login, permissions, or account setup',
                'color' => '#6f42c1', // purple
            ],
            [
                'name' => 'Service Request',
                'description' => 'Requests for new equipment, software, or services',
                'color' => '#198754', // green
            ],
            [
                'name' => 'Other',
                'description' => 'Other issues not covered by existing categories',
                'color' => '#6c757d', // gray
            ],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
