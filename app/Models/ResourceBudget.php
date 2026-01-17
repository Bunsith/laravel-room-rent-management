<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceBudget extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }
}
