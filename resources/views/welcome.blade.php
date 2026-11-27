@extends('layouts.app')

@section('content')
<div class="p-5 mb-4 bg-white rounded-3 border">
    <div class="container-fluid py-3">
        <h1 class="display-5 fw-bold">WiFi Provider Management</h1>
        <p class="col-md-8 fs-5 text-muted">
            Manage customers, service packages, subscriptions, billing and support
            tickets for a local WiFi provider — with role-based access for admins,
            technicians and customers.
        </p>
        @guest
            <a class="btn btn-primary btn-lg" href="{{ route('register') }}">Create an account</a>
            <a class="btn btn-outline-secondary btn-lg" href="{{ route('login') }}">Login</a>
        @else
            <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}">Go to dashboard</a>
        @endguest
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title">Customers &amp; subscriptions</h2>
                <p class="card-text text-muted">Register customers, assign packages and track every subscription from installation to renewal.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title">Billing &amp; payments</h2>
                <p class="card-text text-muted">Monthly bills per subscription period, paid by cash, bank transfer, QRIS or e-wallet — with a clear paid/pending status.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title">Support tickets</h2>
                <p class="card-text text-muted">Customers report outages and slow connections; technicians pick them up by priority and resolve them.</p>
            </div>
        </div>
    </div>
</div>
@endsection
