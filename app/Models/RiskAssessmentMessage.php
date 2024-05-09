<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskAssessmentMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'user_id',
        'risk_assessment_id',
        'attached_files',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class);
    }
}
