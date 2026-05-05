<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'first_name',
        'last_name',
        'profile_photo',
        'company',
        'job_title',
        'birthday',
        'notes',
        'favorite',
    ];

    protected $casts = [
        'birthday' => 'date',
        'favorite' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the contact.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the contact.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the phone numbers for the contact.
     */
    public function phones(): HasMany
    {
        return $this->hasMany(ContactPhone::class);
    }

    /**
     * Get the emails for the contact.
     */
    public function emails(): HasMany
    {
        return $this->hasMany(ContactEmail::class);
    }

    /**
     * Get the addresses for the contact.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(ContactAddress::class);
    }

    /**
     * Get the tags for the contact.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'contact_tag')
            ->withTimestamps();
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Validation rules for creating/updating contacts.
     */
    public static function validationRules(): array
    {
        return [
            'category_id' => 'nullable|integer|exists:categories,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company' => 'nullable|string|max:150',
            'job_title' => 'nullable|string|max:100',
            'birthday' => 'nullable|date',
            'notes' => 'nullable|string|max:5000',
            'favorite' => 'nullable|boolean',
        ];
    }

    /**
     * Scope contacts to a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Search contacts by name, company, phone, or email.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $searchQuery) use ($term) {
            $searchQuery->where('first_name', 'like', '%' . $term . '%')
                ->orWhere('last_name', 'like', '%' . $term . '%')
                ->orWhere('company', 'like', '%' . $term . '%')
                ->orWhereHas('phones', function (Builder $phoneQuery) use ($term) {
                    $phoneQuery->where('phone_number', 'like', '%' . $term . '%');
                })
                ->orWhereHas('emails', function (Builder $emailQuery) use ($term) {
                    $emailQuery->where('email', 'like', '%' . $term . '%');
                });
        });
    }

    /**
     * Limit contacts to a specific category.
     */
    public function scopeInCategory(Builder $query, ?string $categoryId): Builder
    {
        if (blank($categoryId)) {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    /**
     * Filter contacts by one or more tag IDs.
     */
    public function scopeWithTagIds(Builder $query, array $tagIds): Builder
    {
        $tagIds = array_values(array_filter($tagIds));

        if ($tagIds === []) {
            return $query;
        }

        return $query->whereHas('tags', function (Builder $tagQuery) use ($tagIds) {
            $tagQuery->whereIn('tags.id', $tagIds);
        });
    }

    /**
     * Filter contacts by birthday month.
     */
    public function scopeBirthdayMonth(Builder $query, ?string $month): Builder
    {
        if (blank($month)) {
            return $query;
        }

        return $query->whereMonth('birthday', (int) $month);
    }

    /**
     * Filter contacts added within the last 30 days.
     */
    public function scopeRecentlyAddedWindow(Builder $query, bool $enabled): Builder
    {
        if (! $enabled) {
            return $query;
        }

        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Filter contacts by active or trashed status.
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return match ($status) {
            'trashed' => $query->onlyTrashed(),
            'all' => $query->withTrashed(),
            default => $query,
        };
    }

    /**
     * Put favorite contacts first.
     */
    public function scopeFavoritesFirst(Builder $query): Builder
    {
        return $query->orderByDesc('favorite')
            ->orderBy('last_name')
            ->orderBy('first_name');
    }

    /**
     * Sort contacts using the selected ordering.
     */
    public function scopeSortBy(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'za' => $query->orderByDesc('last_name')->orderByDesc('first_name'),
            'newest' => $query->orderByDesc('created_at'),
            'oldest' => $query->orderBy('created_at'),
            'favorites' => $query->favoritesFirst(),
            default => $query->orderBy('last_name')->orderBy('first_name'),
        };
    }
}
