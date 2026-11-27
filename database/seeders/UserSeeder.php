<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin WiFi',
            'email' => 'admin@wifiprovider.local',
            'password' => 'password',
            'phone' => '081234567890',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'teknisi1@wifiprovider.local',
            'password' => 'password',
            'phone' => '081234567891',
            'role' => 'technician',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Agus Wijaya',
            'email' => 'teknisi2@wifiprovider.local',
            'password' => 'password',
            'phone' => '081234567892',
            'role' => 'technician',
            'email_verified_at' => now(),
        ]);

        foreach ([
            ['Siti Rahma', 'customer1@wifiprovider.local', '081234567893'],
            ['Andi Pratama', 'customer2@wifiprovider.local', '081234567894'],
            ['Dewi Lestari', 'customer3@wifiprovider.local', '081234567895'],
        ] as [$name, $email, $phone]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => 'password',
                'phone' => $phone,
                'role' => 'customer',
                'email_verified_at' => now(),
            ]);
        }
    }
}
