<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'email',
        'client_id',
    ];

    public function employeeAccountToken(): HasOne
    {
        return $this->hasOne(EmployeeAccountToken::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

