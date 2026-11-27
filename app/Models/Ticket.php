<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    /**
     * @var list<string>
     */
    public const CATEGORIES = ['installation', 'slow_connection', 'no_connection', 'billing', 'other'];

    /**
     * @var list<string>
     */
    public const PRIORITIES = ['low', 'medium', 'high', 'urgent'];

    /**
     * @var list<string>
     */
    public const STATUSES = ['open', 'in_progress', 'resolved', 'closed'];

    protected $fillable = [
        'customer_id',
        'subscription_id',
        'technician_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'in_progress'], true);
    }

    /**
     * Mark the ticket resolved and stamp the resolution time.
     */
    public function markResolved(): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }
}
