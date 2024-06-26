<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
    ];

    public function riskAssessments(): HasMany
    {
        return $this->hasMany(RiskAssessment::class);
    }
}
