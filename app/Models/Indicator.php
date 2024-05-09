<?php

namespace App\Models;

use Filament\Support\Contracts\HasIcon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'key',
        'formula',
        'value',
        'indicator_type_id',
        'competency_id',
        'data_source_id',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'indicator_tag',
            'tag_id',
            'indicator_id'
        );
    }

    public function risks(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Risk::class,
                'risk_indicator',
                'risk_id',
                'indicator_id'
            )->withPivot('score');
    }

    public function indicatorType(): BelongsTo
    {
        return $this->belongsTo(IndicatorType::class);
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(Competency::class);
    }

    public function dataSource(): BelongsTo
    {
        return $this->belongsTo(DataSource::class);
    }
}
