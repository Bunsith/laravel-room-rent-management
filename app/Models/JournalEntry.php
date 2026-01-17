<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'note',
        'account_type_id',
        'resource_budget_id',
        'amount',
        'currency',
        'method',
        'floor_id',
        'attachment',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function resourceBudget()
    {
        return $this->belongsTo(ResourceBudget::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}
