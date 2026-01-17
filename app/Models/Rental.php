<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'customer_id',
        'people',
        'rent_date',
        'check_in',
        'expected_check_out',
        'check_out',
        'room_fee',
        'deposit_fee',
        'partial_pay',
        'note',
        'status',
    ];

    protected $casts = [
        'rent_date' => 'date',
        'check_in' => 'date',
        'expected_check_out' => 'date',
        'check_out' => 'date',
        'room_fee' => 'decimal:2',
        'deposit_fee' => 'decimal:2',
        'partial_pay' => 'decimal:2',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'rented');
    }
}
