<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'start_date',
        'end_date',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($campaign) {
            if (empty($campaign->slug)) {
                $campaign->slug = Str::slug($campaign->name);
            }
        });
    }

    public function banners(): HasMany
    {
        return $this->hasMany(CampaignBanner::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeHeroSlider($query)
    {
        return $query->where('type', 'hero_slider');
    }

    public function isRunning(): bool
    {
        return $this->is_active
            && $this->start_date <= now()
            && $this->end_date >= now();
    }
}
