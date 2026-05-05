<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'email',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the contact that owns the email.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Validation rules for email addresses.
     */
    public static function validationRules(): array
    {
        return [
            'email' => 'required|email:rfc,dns|unique:contact_emails,email',
            'type' => 'required|string|max:50',
        ];
    }
}
