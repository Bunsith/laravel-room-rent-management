<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'national_id',
        'national_valid_until',
        'passport_id',
        'passport_valid_until',
        'visa_id',
        'visa_valid_until',
        'attachment_file',
    ];

    protected $casts = [
        'national_valid_until' => 'date',
        'passport_valid_until' => 'date',
        'visa_valid_until' => 'date',
        'attachment_file' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
