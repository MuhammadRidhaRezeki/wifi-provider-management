<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Roles available in the system.
     *
     * - admin: manages customers, packages, subscriptions, payments and tickets.
     * - technician: installs subscriptions and resolves tickets assigned to them.
     * - customer: views their own subscription, payments and tickets.
     *
     * @var list<string>
     */
    public const ROLES = ['admin', 'technician', 'customer'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Customer profiles linked to this login account.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Tickets assigned to this technician.
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'technician_id');
    }

    /**
     * Subscriptions installed by this technician.
     */
    public function installedSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'technician_id');
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}
