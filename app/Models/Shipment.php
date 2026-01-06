<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'courier',
        'service',
        'tracking_number',
        'status',
        'weight',
        'shipping_cost',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'tracking_history',
        'shipped_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'tracking_history' => 'array',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pengiriman',
            'picked_up' => 'Diambil Kurir',
            'in_transit' => 'Dalam Perjalanan',
            'out_for_delivery' => 'Sedang Diantar',
            'delivered' => 'Terkirim',
            'returned' => 'Dikembalikan',
            default => $this->status,
        };
    }
}
