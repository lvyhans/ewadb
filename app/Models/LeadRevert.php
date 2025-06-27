<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadRevert extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'ref_no',
        'revert_message',
        'revert_type',
        'submitted_by',
        'team_name',
        'priority',
        'status',
        'metadata',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
    ];

    protected $casts = [
        'metadata' => 'array',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the lead that owns the revert
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the user who resolved this revert
     */
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Scope for active reverts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for resolved reverts
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope for filtering by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for filtering by revert type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('revert_type', $type);
    }

    /**
     * Mark revert as resolved
     */
    public function markAsResolved($userId, $notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => $userId,
            'resolution_notes' => $notes,
        ]);
    }

    /**
     * Check if revert is overdue (older than 2 days for high priority, 7 days for normal)
     */
    public function isOverdue()
    {
        if ($this->status !== 'active') {
            return false;
        }

        $days = $this->priority === 'high' || $this->priority === 'urgent' ? 2 : 7;
        return $this->created_at->addDays($days)->isPast();
    }
}
