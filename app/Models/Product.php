<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'primary_image_url',
        'formatted_price',
        'formatted_sale_price',
        'current_price',
        'discount_percent',
    ];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'stock',
        'sku',
        'sizes',
        'colors',
        'material',
        'weight',
        'is_active',
        'is_featured',
        'is_new_arrival',
        'is_best_seller',
        'total_sold',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'sizes' => 'array',
            'colors' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
        ];
    }

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'LVD-' . strtoupper(Str::random(8));
            }
        });
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNewArrival($query)
    {
        return $query->where('is_new_arrival', true);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('is_best_seller', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereRaw('COALESCE(NULLIF(sale_price, 0), price) BETWEEN ? AND ?', [$min, $max]);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
                ->orWhere('sku', 'like', "%{$keyword}%");
        });
    }

    // Helpers
    public function getCurrentPriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return null;
        }
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function hasDiscount(): bool
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->primaryImage;
        if ($primary) {
            return asset('storage/' . $primary->image);
        }
        $first = $this->images->first();
        return $first ? asset('storage/' . $first->image) : asset('images/placeholder.png');
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    public function getFormattedSalePriceAttribute(): ?string
    {
        return $this->sale_price ? 'Rp ' . number_format((float) $this->sale_price, 0, ',', '.') : null;
    }

    public function getFormattedCurrentPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->current_price, 0, ',', '.');
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
