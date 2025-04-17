<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Staff;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create default staff member for ticket assignment
        Staff::firstOrCreate(
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the default staff member
        Staff::where('email', 'pramuji.arif@example.com')->delete();
    }
}; 