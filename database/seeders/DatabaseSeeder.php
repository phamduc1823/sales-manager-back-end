<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\BusinessField;
use App\Models\Seed;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Tenant::checkCurrent()
            ? $this->runTenantSpecificSeeders()
            : $this->runLandlordSpecificSeeders();
    }

    public function runTenantSpecificSeeders()
    {
        // run tenant specific seeders

        Tenant\Role::query()->insert([
            [
                'name' => 'super-admin',
                'guard_name' => 'api',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'guard_name' => 'api',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'staff',
                'guard_name' => 'api',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Tenant\Customer::query()->create([
            'name' => 'Khách vãng lai',
        ]);
        Tenant\Customer::query()->create([
            'name' => 'Nhà cung cấp NextGo',
            'type' => 1,
        ]);
        $staff = \App\Models\Tenant\User::query()->create([
            'name' => 'staff1',
            'email' => 'staff1@gmail.com',
            'password' => Hash::make('12345678'),
            'location_id' => 1,
        ]);
        $staff->roles()->attach(\App\Models\Tenant\Role::query()->where('name', 'staff')->first()->id);

        Tenant\PrintedForm::query()->create(config('printed_form'));

        $warranty = \App\Models\Tenant\Warranty::query()->insert(
            [
                [
                    "name" => "Bảo hành 6 tháng",
                    "unit" => 1,
                    "period" => 6,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    "name" => "Bảo hành 12 tháng",
                    "unit" => 1,
                    "period" => 12,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    "name" => "Bảo hành 24 tháng",
                    "unit" => 1,
                    "period" => 24,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }

    public function runLandlordSpecificSeeders()
    {
        // run landlord specific seeders
        Role::create([
            'name' => 'super-admin',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'customer',
            'guard_name' => 'web'
        ]);
        $user = User::query()->create([
            'name' => 'tenant_test',
            'email' => 'tenant_test@gmail.com',
            'tel' => '0123456789',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('super-admin');
        $this->call([
            PricingSeeder::class,
            BusinessFieldSeeder::class,
            DataSeedByBusinessFieldSeeder::class,
        ]);

        $bussinessFieldId = BusinessField::query()->where('code', 'FASHION')->first()?->id;

        DB::statement("DROP DATABASE IF EXISTS `tenant1`;");
        Tenant::query()->create([
            'business_name' => 'Cửa hàng bán quần áo',
            'name' => 'tenant1',
            'database' => 'tenant1',
            'business_field_id' => $bussinessFieldId,
            'user_id' => $user->id,
            'address' => 'Hà Nội',
            'pricing_id' => 1,
            'due_at' => Carbon::now()->addDays(30)->format('Y-m-d'),
            "status" => 1,
        ]);

    }
}
