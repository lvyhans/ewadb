<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationCourseOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'country',
        'city',
        'college',
        'course',
        'course_type',
        'fees',
        'duration',
        'college_detail_id',
        'is_primary',
        'priority_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'priority_order' => 'integer',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
