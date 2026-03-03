<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignBanner extends Model
{
    use HasFactory;

    protected $appends = ['image_url', 'mobile_image_url'];

    protected $fillable = [
        'campaign_id',
        'title',
        'subtitle',
        'image',
        'image_mobile',
        'cta_text',
        'cta_link',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->image_mobile ? asset('storage/' . $this->image_mobile) : null;
    }
}
