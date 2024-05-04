<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiskMatrix extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'probabilities',
    ];

    public function risks(): BelongsToMany
    {
        return $this->belongsToMany(
            Risk::class,
            'risk_matrix_risk',
            'risk_id',
            'risk_matrix_id'
        )->withPivot('probabilities');
    }

    public function competencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Competency::class,
            'user_competency',
            'competency_id',
            'risk_matrix_id'
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'risk_matrix_risk',
            'user_id',
            'risk_matrix_id'
        );
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(
            Client::class,
            'risk_matrix_client',
            'client_id',
            'risk_matrix_id'
        );
    }

    public function riskAssessment(): HasMany
    {
        return $this->hasMany(RiskAssessment::class);
    }
}
