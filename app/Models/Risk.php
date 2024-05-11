<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Risk extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
    ];

    public function riskMatrices(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                RiskMatrix::class,
                'risk_matrix_risk',
                'risk_id',
                'risk_matrix_id'
            )->withPivot('probabilities');
    }

    public function indicators(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Indicator::class,
                'risk_indicator',
                'risk_id',
                'indicator_id'
            )->withPivot('score');
    }
}
