<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $technician = User::where('role', 'technician')->first();

        $package10 = Package::where('name', 'Home 10 Mbps')->first();
        $package20 = Package::where('name', 'Home 20 Mbps')->first();
        $package50 = Package::where('name', 'Home 50 Mbps')->first();

        $customers = [
            [
                'user_email' => 'customer1@wifiprovider.local',
                'name' => 'Siti Rahma',
                'phone' => '081234567893',
                'email' => 'customer1@wifiprovider.local',
                'address' => 'Jl. Merdeka No. 12, Lhokseumawe',
                'package' => $package20,
                'start_days_ago' => 20,
                'end_days_ahead' => 10,
                'status' => 'active',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
            ],
            [
                'user_email' => 'customer2@wifiprovider.local',
                'name' => 'Andi Pratama',
                'phone' => '081234567894',
                'email' => 'customer2@wifiprovider.local',
                'address' => 'Jl. T. Nyak Arief No. 45, Lhokseumawe',
                'package' => $package50,
                'start_days_ago' => 45,
                'end_days_ahead' => 5,
                'status' => 'active',
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
            ],
            [
                'user_email' => 'customer3@wifiprovider.local',
                'name' => 'Dewi Lestari',
                'phone' => '081234567895',
                'email' => 'customer3@wifiprovider.local',
                'address' => 'Jl. Darussalam No. 8, Lhokseumawe',
                'package' => $package10,
                'start_days_ago' => 10,
                'end_days_ahead' => 20,
                'status' => 'active',
                'payment_status' => 'pending',
                'payment_method' => 'cash',
            ],
            [
                'user_email' => null,
                'name' => 'Rizky Ramadhan',
                'phone' => '081234567896',
                'email' => null,
                'address' => 'Jl. Elak No. 3, Lhokseumawe',
                'package' => $package20,
                'start_days_ago' => 60,
                'end_days_ahead' => null,
                'status' => 'expired',
                'payment_status' => 'paid',
                'payment_method' => 'cash',
            ],
            [
                'user_email' => null,
                'name' => 'Putri Ayu',
                'phone' => '081234567897',
                'email' => 'putri.ayu@example.com',
                'address' => 'Jl. Banda Aceh-Medan Km. 5, Lhokseumawe',
                'package' => $package10,
                'start_days_ago' => 5,
                'end_days_ahead' => 25,
                'status' => 'paused',
                'payment_status' => 'paid',
                'payment_method' => 'ewallet',
            ],
            [
                'user_email' => null,
                'name' => 'Fajar Nugroho',
                'phone' => '081234567898',
                'email' => null,
                'address' => 'Jl. Samudera No. 21, Lhokseumawe',
                'package' => $package50,
                'start_days_ago' => 15,
                'end_days_ahead' => 15,
                'status' => 'active',
                'payment_status' => 'pending',
                'payment_method' => 'bank_transfer',
            ],
        ];

        foreach ($customers as $data) {
            $customer = Customer::create([
                'user_id' => $data['user_email']
                    ? User::where('email', $data['user_email'])->first()?->id
                    : null,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
                'status' => $data['status'] === 'expired' ? 'inactive' : 'active',
            ]);

            $start = now()->subDays($data['start_days_ago'])->toDateString();
            $end = $data['end_days_ahead'] !== null
                ? now()->addDays($data['end_days_ahead'])->toDateString()
                : now()->subDays(2)->toDateString();

            $subscription = Subscription::create([
                'customer_id' => $customer->id,
                'package_id' => $data['package']->id,
                'technician_id' => $technician?->id,
                'start_date' => $start,
                'end_date' => $end,
                'status' => $data['status'],
                'installation_fee' => 100000,
                'installation_date' => $start,
            ]);

            Payment::create([
                'subscription_id' => $subscription->id,
                'customer_id' => $customer->id,
                'amount' => $data['package']->price_monthly,
                'payment_method' => $data['payment_method'],
                'status' => $data['payment_status'],
                'period_start' => $start,
                'period_end' => $end,
                'reference' => $data['payment_status'] === 'paid' ? 'INV-'.str_pad((string) $subscription->id, 5, '0', STR_PAD_LEFT) : null,
                'paid_at' => $data['payment_status'] === 'paid' ? now()->subDays(2) : null,
            ]);
        }

        // A few realistic support tickets.
        $siti = Customer::where('name', 'Siti Rahma')->first();
        $andi = Customer::where('name', 'Andi Pratama')->first();
        $dewi = Customer::where('name', 'Dewi Lestari')->first();

        Ticket::create([
            'customer_id' => $siti->id,
            'subscription_id' => $siti->subscriptions()->first()->id,
            'title' => 'Internet mati total sejak semalam',
            'description' => 'Modem tidak ada sinyal sama sekali, lampu LOS berkedip merah.',
            'category' => 'no_connection',
            'priority' => 'high',
            'status' => 'open',
        ]);

        Ticket::create([
            'customer_id' => $andi->id,
            'subscription_id' => $andi->subscriptions()->first()->id,
            'technician_id' => $technician?->id,
            'title' => 'Kecepatan lambat di jam malam',
            'description' => 'Kecepatan turun di bawah 10 Mbps setiap pukul 19.00-22.00.',
            'category' => 'slow_connection',
            'priority' => 'medium',
            'status' => 'in_progress',
        ]);

        Ticket::create([
            'customer_id' => $dewi->id,
            'subscription_id' => $dewi->subscriptions()->first()->id,
            'technician_id' => $technician?->id,
            'title' => 'Tagihan bulan ini dobel',
            'description' => 'Tagihan bulan ini tercatat dua kali di aplikasi pembayaran.',
            'category' => 'billing',
            'priority' => 'low',
            'status' => 'resolved',
            'resolved_at' => now()->subDay(),
        ]);
    }
}
