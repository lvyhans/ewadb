<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadWorkExperience extends Model
{
    protected $fillable = [
        'lead_id',
        'join_date',
        'left_date', 
        'company_name',
        'job_position',
        'job_city'
    ];

    protected $casts = [
        'join_date' => 'date',
        'left_date' => 'date',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    // Calculate duration in months
    public function getDurationAttribute()
    {
        if (!$this->join_date || !$this->left_date) {
            return null;
        }

        return $this->join_date->diffInMonths($this->left_date);
    }

    // Get formatted duration
    public function getFormattedDurationAttribute()
    {
        $duration = $this->duration;
        if (!$duration) {
            return 'N/A';
        }

        $years = floor($duration / 12);
        $months = $duration % 12;

        $result = [];
        if ($years > 0) {
            $result[] = $years . ' year' . ($years > 1 ? 's' : '');
        }
        if ($months > 0) {
            $result[] = $months . ' month' . ($months > 1 ? 's' : '');
        }

        return implode(' ', $result);
    }
}
