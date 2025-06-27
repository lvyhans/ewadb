<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadEmploymentHistory extends Model
{
    use HasFactory;

    protected $table = 'lead_employment_history';

    protected $fillable = [
        'lead_id',
        'join_date',
        'left_date',
        'company_name',
        'job_position',
        'job_city',
    ];

    protected $casts = [
        'join_date' => 'date',
        'left_date' => 'date',
    ];

    /**
     * Get the lead that owns this employment history
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
