<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'logo',
        'default_currency',
        'deposit_default',
        'water_rate',
        'electric_rate',
    ];

    protected $casts = [
        'deposit_default' => 'decimal:2',
        'water_rate' => 'decimal:2',
        'electric_rate' => 'decimal:2',
    ];
}
