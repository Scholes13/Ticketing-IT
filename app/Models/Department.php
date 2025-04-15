<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * Get the tickets for the department.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'department', 'name');
    }

    /**
     * Get the staff for the department.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'department', 'name');
    }
}
