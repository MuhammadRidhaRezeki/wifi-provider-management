<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name',
        'speed_mbps',
        'price_monthly',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'speed_mbps' => 'integer',
            'price_monthly' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Monthly price formatted as Indonesian rupiah, e.g. "Rp 150.000".
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->price_monthly, 0, ',', '.');
    }
}
