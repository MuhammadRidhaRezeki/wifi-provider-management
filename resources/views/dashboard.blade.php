@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="h3 mb-1">Welcome, {{ auth()->user()->name }}</h1>
<p class="text-muted mb-4">Signed in as <strong>{{ auth()->user()->role }}</strong> &middot; {{ now()->format('l, d F Y') }}</p>

@if (auth()->user()->isAdmin())
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $stats['customers'] }}</h2>
                    <p class="card-text mb-0">Active customers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $stats['subscriptions'] }}</h2>
                    <p class="card-text mb-0">Active subscriptions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $stats['open_tickets'] }}</h2>
                    <p class="card-text mb-0">Open tickets</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info">
                <div class="card-body">
                    <h2 class="card-title h4">Rp {{ number_format($stats['revenue_this_month'], 0, ',', '.') }}</h2>
                    <p class="card-text mb-0">Revenue this month</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Recent tickets</div>
                <ul class="list-group list-group-flush">
                    @forelse ($recent_tickets as $ticket)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $ticket->title }}</strong>
                                <div class="text-muted small">{{ $ticket->customer->name }} &middot; {{ $ticket->category }}</div>
                            </div>
                            <span class="badge text-bg-{{ $ticket->status === 'open' ? 'danger' : ($ticket->status === 'in_progress' ? 'warning' : 'success') }}">{{ $ticket->status }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No tickets yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Subscriptions expiring soon</div>
                <ul class="list-group list-group-flush">
                    @forelse ($expiring_subscriptions as $subscription)
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <strong>{{ $subscription->customer->name }}</strong>
                                <div class="text-muted small">{{ $subscription->package->name }}</div>
                            </div>
                            <span>{{ $subscription->end_date->format('d M Y') }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Nothing expiring in the next 14 days.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@elseif (auth()->user()->isTechnician())
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $stats['assigned_open_tickets'] }}</h2>
                    <p class="card-text mb-0">Open tickets assigned to you</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $stats['assigned_resolved_tickets'] }}</h2>
                    <p class="card-text mb-0">Tickets you resolved</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $stats['installs'] }}</h2>
                    <p class="card-text mb-0">Installations by you</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Your open tickets (highest priority first)</div>
        <ul class="list-group list-group-flush">
            @forelse ($my_tickets as $ticket)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <strong>{{ $ticket->title }}</strong>
                        <div class="text-muted small">{{ $ticket->customer->name }} &middot; {{ $ticket->category }}</div>
                    </div>
                    <span class="badge text-bg-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'secondary') }}">{{ $ticket->priority }}</span>
                </li>
            @empty
                <li class="list-group-item text-muted">No open tickets assigned to you.</li>
            @endforelse
        </ul>
    </div>
@else
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $stats['package'] ?? '—' }}</h2>
                    <p class="card-text mb-0">Your package ({{ $stats['subscription_status'] ?? 'no subscription' }})</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $my_subscription?->end_date?->format('d M Y') ?? '—' }}</h2>
                    <p class="card-text mb-0">Subscription ends</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $stats['pending_payments'] }}</h2>
                    <p class="card-text mb-0">Pending payments</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title h4">{{ $stats['open_tickets'] }}</h2>
                    <p class="card-text mb-0">Your open tickets</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Recent payments</div>
                <ul class="list-group list-group-flush">
                    @forelse ($my_payments as $payment)
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <strong>{{ $payment->formatted_amount }}</strong>
                                <div class="text-muted small">{{ $payment->period_start->format('d M Y') }} – {{ $payment->period_end->format('d M Y') }}</div>
                            </div>
                            <span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">{{ $payment->status }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No payments yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Your tickets</div>
                <ul class="list-group list-group-flush">
                    @forelse ($my_tickets as $ticket)
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <strong>{{ $ticket->title }}</strong>
                                <div class="text-muted small">{{ $ticket->category }}</div>
                            </div>
                            <span class="badge text-bg-{{ $ticket->status === 'open' ? 'danger' : ($ticket->status === 'in_progress' ? 'warning' : 'success') }}">{{ $ticket->status }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No tickets yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endif
@endsection
