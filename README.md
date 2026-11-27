# WiFi Provider Management

A Laravel 12 application to run a local WiFi provider (RT/RW Net style):
customers, service packages, subscriptions, monthly billing and support
tickets — with role-based access for **Admin**, **Technician** and
**Customer**.

Built in slices. Slice 1 (this release) is the foundation: database schema,
Eloquent models, seed data, role middleware, authentication and a
role-aware dashboard. CRUD screens for customers, packages, subscriptions,
tickets and payments land in the following slices.

## Features

- **Roles** — Admin manages everything, Technicians install subscriptions
  and resolve assigned tickets, Customers see only their own subscription,
  payments and tickets (`role` middleware + `User::ROLES`).
- **Customers** — profile with phone, address and ID card number; may be
  linked to a login account or registered walk-in by an admin.
- **Packages** — internet plans with speed (Mbps) and monthly price (IDR).
- **Subscriptions** — customer + package + installing technician, with
  start/end dates, installation fee and lifecycle status
  (`active`, `paused`, `expired`, `cancelled`).
- **Payments** — one bill per subscription period, paid by cash, bank
  transfer, QRIS or e-wallet; `pending` / `paid` / `failed`.
- **Tickets** — support requests with category (`installation`,
  `slow_connection`, `no_connection`, `billing`, `other`), priority and
  status; technicians resolve them with a timestamp.
- **Dashboard** — admin sees customer/subscription counts, open tickets,
  revenue this month, recent tickets and subscriptions expiring soon;
  technicians see their queue; customers see their package, bills and
  tickets.

## Tech stack

- PHP 8.2+, Laravel 12, MySQL (SQLite works for local development)
- Blade + Bootstrap 5 (CDN, no frontend build step)

## Setup

```bash
git clone https://github.com/MuhammadRidhaRezeki/wifi-provider-management.git
cd wifi-provider-management
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` for your database (defaults to MySQL `wifi_provider`), then:

```bash
php artisan migrate --seed
php artisan serve
```

Open http://localhost:8000.

### Demo accounts (seeded, password: `password`)

| Role       | Email                        |
| ---------- | ---------------------------- |
| Admin      | admin@wifiprovider.local     |
| Technician | teknisi1@wifiprovider.local  |
| Technician | teknisi2@wifiprovider.local  |
| Customer   | customer1@wifiprovider.local |
| Customer   | customer2@wifiprovider.local |
| Customer   | customer3@wifiprovider.local |

Seed data also includes 4 packages (10–100 Mbps, Rp 150.000–500.000/month),
6 customers with subscriptions and payments, and 3 support tickets.

## Data model

```
users ──< customers ──< subscriptions >── packages
   │          │  │            │  │
   │          │  │            │  └──< payments
   │          │  └──< tickets │
   │          └─────< payments│
   └──< tickets (technician)  └──< tickets (subscription)
   └──< subscriptions (technician)
```

- `users.role`: `admin` | `technician` | `customer`
- `customers.status`: `active` | `inactive` | `suspended`
- `subscriptions.status`: `active` | `paused` | `expired` | `cancelled`
- `tickets.status`: `open` | `in_progress` | `resolved` | `closed`
- `payments.status`: `pending` | `paid` | `failed`

## Project structure

```
app/
  Http/
    Controllers/AuthController.php      # login / register / logout
    Controllers/DashboardController.php # role-aware dashboard
    Middleware/RoleMiddleware.php       # ->middleware('role:admin,...')
  Models/
    User.php Customer.php Package.php
    Subscription.php Ticket.php Payment.php
database/
  migrations/  # users (+role/phone), customers, packages,
                # subscriptions, tickets, payments
  seeders/      # UserSeeder, PackageSeeder, CustomerSeeder
resources/views/
  layouts/app.blade.php  dashboard.blade.php  welcome.blade.php
  auth/login.blade.php   auth/register.blade.php
routes/web.php
```

## Roadmap

- [x] Slice 1 — Foundation: schema, models, seeders, roles, auth, dashboard
- [ ] Slice 2 — Customer CRUD (admin) + customer profile page
- [ ] Slice 3 — Package & subscription management, expiry reminders
- [ ] Slice 4 — Ticket workflow (create, assign, resolve, close)
- [ ] Slice 5 — Payments: billing generation, receipts, revenue report
- [ ] Slice 6 — Feature tests (auth, roles, dashboard, billing)

## License

MIT — see [LICENSE](LICENSE).
