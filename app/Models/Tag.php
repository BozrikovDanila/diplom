<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
    ];

    public function indicators(): BelongsToMany
    {
        return $this->belongsToMany(
            Indicator::class,
            'indicator_tag',
            'indicator_id',
            'tag_id'
        );
    }
}
