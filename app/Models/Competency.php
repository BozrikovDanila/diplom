<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',

    ];

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_competency',
            'competency_id',
            'user_id',
        );
    }

    public function riskMatrices(): BelongsToMany
    {
        return $this->belongsToMany(
            RiskMatrix::class,
            'user_competency',
            'competency_id',
            'risk_matrix_id',
        );
    }
}
