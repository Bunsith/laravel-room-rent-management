<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'invoice_no',
        'invoice_date',
        'total_amount',
        'total_paid',
        'due_amount',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'total_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function recalculateTotals(): void
    {
        $totalAmount = (float) $this->items()->sum('amount');
        $totalPaid = (float) $this->payments()->sum('amount');
        $dueAmount = max($totalAmount - $totalPaid, 0);

        $status = 'unpaid';
        if ($dueAmount <= 0) {
            $status = 'paid';
        } elseif ($totalPaid > 0) {
            $status = 'partial';
        }

        $this->forceFill([
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'due_amount' => $dueAmount,
            'status' => $status,
        ])->save();
    }
}
