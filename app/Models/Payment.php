<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /**
     * @var list<string>
     */
    public const METHODS = ['cash', 'bank_transfer', 'qris', 'ewallet'];

    /**
     * @var list<string>
     */
    public const STATUSES = ['pending', 'paid', 'failed'];

    protected $fillable = [
        'subscription_id',
        'customer_id',
        'amount',
        'payment_method',
        'status',
        'period_start',
        'period_end',
        'reference',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'period_start' => 'date',
            'period_end' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Amount formatted as Indonesian rupiah, e.g. "Rp 150.000".
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp '.number_format($this->amount, 0, ',', '.');
    }

    /**
     * Record a successful payment and stamp when it was paid.
     */
    public function markPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }
}
