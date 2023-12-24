<?php

namespace Database\Seeders;

use App\Models\Pricing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    /**
     * Run the database list.
     *
     * @return void
     */
    public function run()
    {
        Pricing::create([
            'name' => 'Dùng thử',
            'max_locations' => 1,
            'max_users' => 1,
            'price' => 0,
            'expiry_day'=>14
        ]);
        Pricing::create([
            'name' => 'Cơ bản - 1 năm',
            'max_locations' => 3,
            'max_users' => 3,
            'price' => 1400000,
            'expiry_day'=>365
        ]);
        Pricing::create([
            'name' => 'Tiêu chuẩn - 1 năm',
            'max_locations' => 5,
            'max_users' => 5,
            'price' => 2000000,
            'expiry_day'=>365
        ]);
        Pricing::create([
            'name' => 'Nâng cao - 1 năm',
            'max_locations' => 10,
            'max_users' => 10,
            'price' => 3000000,
            'expiry_day'=>365
        ]);
        Pricing::create([
            'name' => 'Cơ bản - 3 năm',
            'max_locations' => 3,
            'max_users' => 3,
            'price' => 3500000,
            'expiry_day'=>1095
        ]);
        Pricing::create([
            'name' => 'Tiêu chuẩn - 3 năm',
            'max_locations' => 5,
            'max_users' => 5,
            'price' => 5000000,
            'expiry_day'=>1095
        ]);
        Pricing::create([
            'name' => 'Nâng cao - 3 năm',
            'max_locations' => 10,
            'max_users' => 10,
            'price' => 7500000,
            'expiry_day'=>1095
        ]);
        Pricing::create([
            'name' => 'Cơ bản - 5 năm',
            'max_locations' => 3,
            'max_users' => 3,
            'price' => 5000000,
            'expiry_day'=>1825
        ]);
        Pricing::create([
            'name' => 'Tiêu chuẩn - 5 năm',
            'max_locations' => 5,
            'max_users' => 5,
            'price' => 7500000,
            'expiry_day'=>1825
        ]);
        Pricing::create([
            'name' => 'Nâng cao - 5 năm',
            'max_locations' => 10,
            'max_users' => 10,
            'price' => 10000000,
            'expiry_day'=>1825
        ]);
    }
}
