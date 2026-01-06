<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'province',
        'city',
        'district',
        'postal_code',
        'address_detail',
        'notes',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Helpers
    public function getFullAddressAttribute(): string
    {
        return "{$this->address_detail}, {$this->district}, {$this->city}, {$this->province} {$this->postal_code}";
    }

    public function getDisplayLabelAttribute(): string
    {
        return $this->label ?? 'Alamat';
    }

    public function setPrimary(): void
    {
        // Remove primary from other addresses
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        $this->update(['is_primary' => true]);
    }
}
