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
        'indicator_key',
        'formula',
        'indicator_value',
        'indicator_type_id',
        'competency_id',
        'data_source_id',
    ];

    protected $attributes = [
        'formula' => "",
        'indicator_value' => "[]",

    ];

    protected $casts = [
        'indicator_value' => 'array',

    ];


    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'indicator_tag',
            'indicator_id',
            'tag_id'
        );
    }

    public function risks(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Risk::class,
                'risk_indicator',
                'indicator_id',
                'risk_id'
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
