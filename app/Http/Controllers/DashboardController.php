<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Role-aware dashboard.
     *
     * - Admin sees business-wide stats, recent tickets and subscriptions
     *   expiring soon.
     * - Technician sees tickets assigned to them and their installs.
     * - Customer sees their own subscription, payments and tickets.
     */
    public function index(): View
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return view('dashboard', [
                'stats' => [
                    'customers' => Customer::active()->count(),
                    'subscriptions' => Subscription::active()->count(),
                    'open_tickets' => Ticket::open()->count(),
                    'revenue_this_month' => Payment::paid()
                        ->whereMonth('paid_at', now()->month)
                        ->whereYear('paid_at', now()->year)
                        ->sum('amount'),
                ],
                'recent_tickets' => Ticket::with(['customer', 'technician'])
                    ->latest()
                    ->take(5)
                    ->get(),
                'expiring_subscriptions' => Subscription::with(['customer', 'package'])
                    ->expiringSoon(14)
                    ->orderBy('end_date')
                    ->take(5)
                    ->get(),
                'my_tickets' => null,
                'my_subscription' => null,
                'my_payments' => null,
            ]);
        }

        if ($user->isTechnician()) {
            return view('dashboard', [
                'stats' => [
                    'assigned_open_tickets' => $user->assignedTickets()->open()->count(),
                    'assigned_resolved_tickets' => $user->assignedTickets()->where('status', 'resolved')->count(),
                    'installs' => $user->installedSubscriptions()->count(),
                ],
                'recent_tickets' => null,
                'expiring_subscriptions' => null,
                'my_tickets' => $user->assignedTickets()
                    ->with(['customer', 'subscription'])
                    ->open()
                    ->orderByRaw("CASE priority WHEN 'urgent' THEN 0 WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
                    ->take(10)
                    ->get(),
                'my_subscription' => null,
                'my_payments' => null,
            ]);
        }

        // Customer role: everything is scoped to their own profile.
        $customer = Customer::where('user_id', $user->id)->first();
        $subscription = $customer?->activeSubscription;

        return view('dashboard', [
            'stats' => [
                'subscription_status' => $subscription?->status,
                'package' => $subscription?->package?->name,
                'pending_payments' => $customer
                    ? Payment::pending()->where('customer_id', $customer->id)->count()
                    : 0,
                'open_tickets' => $customer
                    ? Ticket::open()->where('customer_id', $customer->id)->count()
                    : 0,
            ],
            'recent_tickets' => null,
            'expiring_subscriptions' => null,
            'my_tickets' => $customer
                ? $customer->tickets()->latest()->take(5)->get()
                : collect(),
            'my_subscription' => $subscription,
            'my_payments' => $customer
                ? $customer->payments()->with('subscription.package')->latest()->take(5)->get()
                : collect(),
        ]);
    }
}
