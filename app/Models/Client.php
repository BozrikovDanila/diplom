<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'email',
        'phone',
        'employees_number',
        'assessments_number',
        'duration',
        'instant_access',
        'INN',
        'client_status_id',
    ];

    public function clientStatus(): BelongsTo
    {
        return $this->belongsTo(ClientStatus::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function riskAssessments(): HasMany
    {
        return $this->hasMany(RiskAssessment::class);
    }

    public function riskMatrices(): BelongsToMany
    {
        return $this->belongsToMany(
            RiskMatrix::class,
            'risk_matrix_client',
            'risk_matrix_id',
            'client_id'
        );
    }
}
