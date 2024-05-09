<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'description',
    ];

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }
}
