<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'position',
        'gender',
        'phone',
        'department',
        'is_active',
        'user_id',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
