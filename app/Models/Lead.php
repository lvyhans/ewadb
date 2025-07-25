<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_no',
        'name',
        'dob',
        'father_name',
        'phone',
        'alt_phone',
        'email',
        'city',
        'address',
        'preferred_country',
        'preferred_city',
        'preferred_college',
        'preferred_course',
        'travel_history',
        'any_refusal',
        'spouse_name',
        'any_gap',
        'score_type',
        'ielts_listening',
        'ielts_reading',
        'ielts_writing',
        'ielts_speaking',
        'ielts_overall',
        'pte_listening',
        'pte_reading',
        'pte_writing',
        'pte_speaking',
        'pte_overall',
        'duolingo_listening',
        'duolingo_reading',
        'duolingo_writing',
        'duolingo_speaking',
        'duolingo_overall',
        'last_qualification',
        'twelfth_year',
        'twelfth_percentage',
        'twelfth_major',
        'tenth_year',
        'tenth_percentage',
        'tenth_major',
        'diploma_year',
        'diploma_percentage',
        'diploma_major',
        'graduation_year',
        'graduation_percentage',
        'graduation_major',
        'post_graduation_year',
        'post_graduation_percentage',
        'post_graduation_major',
        'previous_visa_application',
        'source',
        'reference_name',
        'visa_counselor',
        'remarks',
        'status',
        'assigned_to',
        'created_by',
    ];

    protected $casts = [
        'dob' => 'date',
        'ielts_listening' => 'decimal:1',
        'ielts_reading' => 'decimal:1',
        'ielts_writing' => 'decimal:1',
        'ielts_speaking' => 'decimal:1',
        'ielts_overall' => 'decimal:1',
    ];

    /**
     * Get the employment history for the lead
     */
    public function employmentHistory()
    {
        return $this->hasMany(LeadEmploymentHistory::class);
    }

    /**
     * Get the followups for the lead
     */
    public function followups()
    {
        return $this->hasMany(LeadFollowup::class);
    }

    /**
     * Get the reverts for the lead
     */
    public function reverts()
    {
        return $this->hasMany(LeadRevert::class);
    }

    /**
     * Get active reverts for the lead
     */
    public function activeReverts()
    {
        return $this->hasMany(LeadRevert::class)->where('status', 'active');
    }

    /**
     * Get resolved reverts for the lead
     */
    public function resolvedReverts()
    {
        return $this->hasMany(LeadRevert::class)->where('status', 'resolved');
    }

    /**
     * Get the user who created this lead
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user assigned to this lead
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the latest followup
     */
    public function latestFollowup()
    {
        return $this->hasOne(LeadFollowup::class)->latestOfMany();
    }

    /**
     * Get pending followups
     */
    public function pendingFollowups()
    {
        return $this->hasMany(LeadFollowup::class)->where('status', 'scheduled');
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by assigned user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope for filtering leads accessible by a specific user based on their role
     */
    public function scopeAccessibleByUser($query, $user)
    {
        if ($user->hasRole('admin')) {
            // Admin can see their own leads and their members' leads
            return $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)        // Their own leads
                  ->orWhere('assigned_to', $user->id)     // Leads assigned to them
                  ->orWhereHas('creator', function($subQ) use ($user) {
                      $subQ->where('admin_id', $user->id); // Leads created by their members
                  })
                  ->orWhereHas('assignedUser', function($subQ) use ($user) {
                      $subQ->where('admin_id', $user->id); // Leads assigned to their members
                  });
            });
        } else {
            // Member can only see their own leads (created by them or assigned to them)
            return $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }
    }

    /**
     * Scope for filtering leads by user role access
     */
    public function scopeForUserRole($query, $user)
    {
        return $this->scopeAccessibleByUser($query, $user);
    }

    /**
     * Generate unique reference number
     */
    public static function generateRefNo()
    {
        $lastLead = self::latest()->first();
        $lastId = $lastLead ? $lastLead->id : 0;
        return 'LEAD' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
    }
}
