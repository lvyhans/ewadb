<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'type',
        'subject',
        'description',
        'status',
        'scheduled_at',
        'completed_at',
        'outcome',
        'next_followup',
        'priority',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'next_followup' => 'datetime',
    ];

    /**
     * Get the lead that owns this followup
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the user who created this followup
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for pending followups
     */
    public function scopePending($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope for completed followups
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for today's followups
     */
    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    /**
     * Scope for overdue followups
     */
    public function scopeOverdue($query)
    {
        return $query->where('scheduled_at', '<', now())
                    ->where('status', 'scheduled');
    }
}
