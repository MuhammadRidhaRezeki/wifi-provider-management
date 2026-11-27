<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    /**
     * Subscription lifecycle statuses.
     *
     * @var list<string>
     */
    public const STATUSES = ['active', 'paused', 'expired', 'cancelled'];

    protected $fillable = [
        'customer_id',
        'package_id',
        'technician_id',
        'start_date',
        'end_date',
        'status',
        'installation_fee',
        'installation_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'installation_date' => 'date',
            'installation_fee' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Subscriptions whose paid period ends within the given number of days.
     */
    public function scopeExpiringSoon($query, int $days = 14)
    {
        return $query->where('status', 'active')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', now()->addDays($days));
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * True when an active subscription has passed its end date.
     * A scheduled job (or admin review) flips these to "expired".
     */
    public function isOverdue(): bool
    {
        return $this->isActive()
            && $this->end_date !== null
            && $this->end_date->isPast();
    }

    /**
     * Monthly charge, taken from the linked package.
     */
    public function getMonthlyPriceAttribute(): ?int
    {
        return $this->package?->price_monthly;
    }
}
