<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_number',
        'lead_ref_no',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'nationality',
        'passport_number',
        'passport_expiry',
        'marital_status',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'preferred_country',
        'preferred_city',
        'preferred_college',
        'course_level',
        'field_of_study',
        'intake_year',
        'intake_month',
        'english_proficiency',
        'ielts_score',
        'toefl_score',
        'pte_score',
        'other_english_test',
        'bachelor_degree',
        'bachelor_university',
        'bachelor_percentage',
        'bachelor_year',
        'master_degree',
        'master_university',
        'master_percentage',
        'master_year',
        'twelfth_board',
        'twelfth_school',
        'twelfth_percentage',
        'twelfth_year',
        'tenth_board',
        'tenth_school',
        'tenth_percentage',
        'tenth_year',
        'visa_refusal_history',
        'refusal_details',
        'travel_history',
        'financial_support',
        'sponsor_name',
        'sponsor_relationship',
        'estimated_budget',
        'remarks',
        'status',
        'created_by',
        'assigned_to',
        'visa_counselor',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
        'visa_refusal_history' => 'boolean',
        'travel_history' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function employmentHistory(): HasMany
    {
        return $this->hasMany(ApplicationEmployment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function followups(): HasMany
    {
        return $this->hasMany(ApplicationFollowup::class);
    }

    // Generate unique application number
    public static function generateApplicationNumber()
    {
        $lastApplication = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastApplication ? (int) substr($lastApplication->application_number, 4) : 0;
        return 'APP-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
    }

    // Auto-fill from lead
    public static function createFromLead($leadRefNo)
    {
        $lead = Lead::where('lead_ref_no', $leadRefNo)->first();
        
        if (!$lead) {
            return null;
        }

        $applicationData = [
            'application_number' => self::generateApplicationNumber(),
            'lead_ref_no' => $lead->lead_ref_no,
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'date_of_birth' => $lead->date_of_birth,
            'gender' => $lead->gender,
            'nationality' => $lead->nationality,
            'passport_number' => $lead->passport_number,
            'passport_expiry' => $lead->passport_expiry,
            'marital_status' => $lead->marital_status,
            'address' => $lead->address,
            'city' => $lead->city,
            'state' => $lead->state,
            'postal_code' => $lead->postal_code,
            'country' => $lead->country,
            'emergency_contact_name' => $lead->emergency_contact_name,
            'emergency_contact_phone' => $lead->emergency_contact_phone,
            'emergency_contact_relationship' => $lead->emergency_contact_relationship,
            'preferred_country' => $lead->preferred_country,
            'preferred_city' => $lead->preferred_city,
            'preferred_college' => $lead->preferred_college,
            'course_level' => $lead->course_level,
            'field_of_study' => $lead->field_of_study,
            'intake_year' => $lead->intake_year,
            'intake_month' => $lead->intake_month,
            'english_proficiency' => $lead->english_proficiency,
            'ielts_score' => $lead->ielts_score,
            'toefl_score' => $lead->toefl_score,
            'pte_score' => $lead->pte_score,
            'other_english_test' => $lead->other_english_test,
            'bachelor_degree' => $lead->bachelor_degree,
            'bachelor_university' => $lead->bachelor_university,
            'bachelor_percentage' => $lead->bachelor_percentage,
            'bachelor_year' => $lead->bachelor_year,
            'master_degree' => $lead->master_degree,
            'master_university' => $lead->master_university,
            'master_percentage' => $lead->master_percentage,
            'master_year' => $lead->master_year,
            'twelfth_board' => $lead->twelfth_board,
            'twelfth_school' => $lead->twelfth_school,
            'twelfth_percentage' => $lead->twelfth_percentage,
            'twelfth_year' => $lead->twelfth_year,
            'tenth_board' => $lead->tenth_board,
            'tenth_school' => $lead->tenth_school,
            'tenth_percentage' => $lead->tenth_percentage,
            'tenth_year' => $lead->tenth_year,
            'visa_refusal_history' => $lead->visa_refusal_history,
            'refusal_details' => $lead->refusal_details,
            'travel_history' => $lead->travel_history,
            'financial_support' => $lead->financial_support,
            'sponsor_name' => $lead->sponsor_name,
            'sponsor_relationship' => $lead->sponsor_relationship,
            'estimated_budget' => $lead->estimated_budget,
            'remarks' => $lead->remarks,
            'status' => 'pending',
            'created_by' => $lead->created_by,
            'assigned_to' => $lead->assigned_to,
            'visa_counselor' => $lead->visa_counselor,
        ];

        return $applicationData;
    }
}
