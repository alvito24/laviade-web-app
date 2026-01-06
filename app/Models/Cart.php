<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function selectedItems(): HasMany
    {
        return $this->hasMany(CartItem::class)->where('is_selected', true);
    }

    // Helpers
    public function getTotalItemsAttribute(): int
    {
        return $this->items()->sum('quantity');
    }

    public function getSelectedItemsCountAttribute(): int
    {
        return $this->selectedItems()->sum('quantity');
    }

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getSelectedSubtotalAttribute(): float
    {
        return $this->selectedItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedSelectedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->selected_subtotal, 0, ',', '.');
    }

    // Static helper to get or create cart for user
    public static function getOrCreateForUser(int $userId): self
    {
        return self::firstOrCreate(['user_id' => $userId]);
    }

    public function clearSelectedItems(): void
    {
        $this->selectedItems()->delete();
    }

    public function isEmpty(): bool
    {
        return $this->items()->count() === 0;
    }
}
