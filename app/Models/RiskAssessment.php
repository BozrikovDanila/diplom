<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiskAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'completion_date',
        'total_score',
        'assessment_status_id',
        'risk_matrix_id',
        'org_name',
        'INN',
        'selected_risks',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assessmentStatus(): BelongsTo
    {
        return $this->belongsTo(AssessmentStatus::class);
    }

    public function riskMatrix(): BelongsTo
    {
        return $this->belongsTo(RiskMatrix::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(RiskAssessmentMessage::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'risk_assessment_user',
            'user_id',
            'risk_assessment_id'
        );
    }
}
