<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'phone_number',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the contact that owns the phone number.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Validation rules for phone numbers.
     */
    public static function validationRules(): array
    {
        return [
            'phone_number' => 'required|string|min:10|max:20|unique:contact_phones,phone_number',
            'type' => 'required|in:mobile,home,work',
        ];
    }
}
