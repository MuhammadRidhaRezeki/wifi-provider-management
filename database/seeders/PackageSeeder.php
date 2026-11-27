<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Home 10 Mbps',
                'speed_mbps' => 10,
                'price_monthly' => 150000,
                'description' => 'For light browsing, chatting and one streaming device.',
            ],
            [
                'name' => 'Home 20 Mbps',
                'speed_mbps' => 20,
                'price_monthly' => 200000,
                'description' => 'For small households: streaming, online classes and video calls.',
            ],
            [
                'name' => 'Home 50 Mbps',
                'speed_mbps' => 50,
                'price_monthly' => 300000,
                'description' => 'For families: several devices, HD streaming and online gaming.',
            ],
            [
                'name' => 'Bisnis 100 Mbps',
                'speed_mbps' => 100,
                'price_monthly' => 500000,
                'description' => 'For shops and small offices: high traffic, CCTV and POS systems.',
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
