<?php

namespace Database\Seeders;

use App\Models\BusinessField;
use App\Models\Tenant;
use App\Models\Tenant\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tenants = Tenant::query()->get();

        echo "Seeding config for tenants" . PHP_EOL;
        echo "====================================================" . PHP_EOL;

        foreach ($tenants as $tenant) {

            echo "Seeding config for tenant: {$tenant->name}........" . PHP_EOL;

            $tenant->makeCurrent();

            if (Config::query()->count() > 0) {
                echo "Config already seeded for tenant: {$tenant->name}" . PHP_EOL;
                echo "====================================================" . PHP_EOL;
                continue;
            }

            $user = $tenant->user;

            $businessFieldId = $tenant->business_field_id;

            $businessField = BusinessField::query()->find($businessFieldId);

            $config = [
                'business_name' => $tenant->business_name,
                'tel' => $user->tel,
                'email' => $user->email,
                'address_detail' => $tenant->address,
                'business_field_code' => $businessField->code,
            ];

            Config::query()->create($config);

            echo "Seeding config for tenant: {$tenant->name} successfully" . PHP_EOL;
            echo "====================================================" . PHP_EOL;
        }
    }
}
