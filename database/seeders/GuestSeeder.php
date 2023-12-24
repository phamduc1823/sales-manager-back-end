<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $customerName = 'Khách vãng lai';
        $tenants = Tenant::query()->get();

        echo "Seeding guest for tenants" . PHP_EOL;
        echo "====================================================" . PHP_EOL;

        foreach ($tenants as $tenant) {

            echo "Seeding guest for tenant: {$tenant->name}........" . PHP_EOL;

            $tenant->makeCurrent();

            if (Tenant\Customer::query()->where('name', 'like', "%{$customerName}%")->count() > 0) {
                echo "Guest already seeded for tenant: {$tenant->name}" . PHP_EOL;
                echo "====================================================" . PHP_EOL;
                continue;
            }

            $customer = [
                'name' => $customerName,
            ];

            Tenant\Customer::query()->create($customer);

            echo "Seeding guest for tenant: {$tenant->name} successfully" . PHP_EOL;
            echo "====================================================" . PHP_EOL;
        }
    }
}
