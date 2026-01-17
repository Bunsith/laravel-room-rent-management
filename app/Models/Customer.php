<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'dob',
        'email',
        'country',
        'member_count',
        'address1',
        'address2',
        'note',
        'photo',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function document()
    {
        return $this->hasOne(CustomerDocument::class);
    }

    public function phones()
    {
        return $this->hasMany(CustomerPhone::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }

    public function missingDocuments(): array
    {
        $required = [
            'National' => ['national_id', 'national_valid_until'],
            'Passport' => ['passport_id', 'passport_valid_until'],
            'Visa' => ['visa_id', 'visa_valid_until'],
        ];

        if (!$this->relationLoaded('document')) {
            $this->load('document');
        }

        $document = $this->document;
        if (!$document) {
            return array_keys($required);
        }

        $missing = [];
        foreach ($required as $label => $fields) {
            foreach ($fields as $field) {
                if (empty($document->{$field})) {
                    $missing[] = $label;
                    break;
                }
            }
        }

        return $missing;
    }

    public function expiredDocuments(?Carbon $today = null): array
    {
        if (!$this->relationLoaded('document')) {
            $this->load('document');
        }

        $document = $this->document;
        if (!$document) {
            return [];
        }

        $today = $today ?: now()->startOfDay();
        $expired = [];

        if ($document->national_valid_until && $document->national_valid_until->lt($today)) {
            $expired[] = 'National';
        }

        if ($document->passport_valid_until && $document->passport_valid_until->lt($today)) {
            $expired[] = 'Passport';
        }

        if ($document->visa_valid_until && $document->visa_valid_until->lt($today)) {
            $expired[] = 'Visa';
        }

        return $expired;
    }
}
