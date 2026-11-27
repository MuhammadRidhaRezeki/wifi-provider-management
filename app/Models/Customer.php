<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    /**
     * Customer account statuses.
     *
     * @var list<string>
     */
    public const STATUSES = ['active', 'inactive', 'suspended'];

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'address',
        'id_card_number',
        'status',
        'notes',
    ];

    /**
     * The login account this customer profile belongs to, if any.
     * Walk-in customers registered by an admin may have no login yet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * The customer's currently running subscription, if any.
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latestOfMany();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
