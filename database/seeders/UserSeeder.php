<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $user = User::create([
            'name' => 'Werkudara IT Admin',
            'email' => 'it@werkudara.com',
            'password' => Hash::make('werkudara88'),
        ]);
        
        // Create staff record for admin user
        Staff::create([
            'name' => 'Werkudara IT Admin',
            'email' => 'it@werkudara.com',
            'position' => 'IT Manager',
            'department' => 'IT Department',
            'is_active' => true,
            'user_id' => $user->id,
        ]);
        
        // Create additional staff members
        $staff = [
            [
                'name' => 'Support Technician',
                'email' => 'support@wgticket.com',
                'position' => 'IT Support',
                'department' => 'IT Department',
                'is_active' => true,
            ],
            [
                'name' => 'Network Specialist',
                'email' => 'network@wgticket.com',
                'position' => 'Network Admin',
                'department' => 'IT Department',
                'is_active' => true,
            ],
        ];
        
        foreach ($staff as $member) {
            Staff::create($member);
        }
    }
}
