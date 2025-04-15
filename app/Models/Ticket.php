<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'requester_name',
        'requester_email',
        'requester_phone',
        'department',
        'status',
        'priority',
        'category_id',
        'assigned_to',
        'follow_up_at',
        'resolved_at',
    ];
    
    protected $casts = [
        'follow_up_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
    
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }
    
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
    
    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }
    
    public function getFollowUpTimeAttribute()
    {
        if ($this->follow_up_at && $this->created_at) {
            return $this->follow_up_at->diffInHours($this->created_at);
        }
        return null;
    }
    
    public function getProcessingTimeAttribute()
    {
        if ($this->resolved_at && $this->follow_up_at) {
            return $this->resolved_at->diffInHours($this->follow_up_at);
        }
        return null;
    }
    
    public function getTotalTimeAttribute()
    {
        if ($this->resolved_at && $this->created_at) {
            return $this->resolved_at->diffInHours($this->created_at);
        }
        return null;
    }
}
