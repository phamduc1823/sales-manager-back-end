<?php

namespace App\Models;

use App\Http\Controllers\Tenant\LocationController;
use App\Models\Tenant\Config;
use App\Models\Tenant\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Actions\MigrateTenantAction;
use Spatie\Multitenancy\Commands\TenantsArtisanCommand;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

class Tenant extends SpatieTenant
{
    use SoftDeletes;

    protected $table = 'tenants';
    protected $fillable = [
        'business_name',
        "name",
        "domain",
        "database",
        "business_field_id",
        "user_id",
        "pricing_id",
        'address',
        "due_at",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    protected static function booted()
    {
        static::created(fn(Tenant $model) => $model->createDatabase());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business_field()
    {
        return $this->belongsTo(BusinessField::class, 'business_field_id');
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class, 'pricing_id');
    }

    public function createDatabase()
    {
        // add logic to create database
        try {

            $this->makeCurrent();
            $status = DB::statement("CREATE DATABASE IF NOT EXISTS `{$this->database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
            if ($status) {
                Artisan::call(TenantsArtisanCommand::class, [

                    'artisanCommand' => 'migrate --path=database/migrations/tenant --database=tenant --seed',

                    '--tenant' => $this->id,

                ]);


                $user = $this->user;

                $businessFieldId = $this->business_field_id;

                $businessField = BusinessField::query()->find($businessFieldId);

                $config = [
                    'business_name' => $this->business_name,
                    'tel' => $user->tel,
                    'email' => $user->email,
                    'address_detail' => $this->address,
                    'business_field_code' => $businessField->code,
                ];

                Config::query()->create($config);

                $seedsByBusinessField = Seed::query()
                    ->whereHas('businessFieldSeed', function ($query) use ($businessFieldId) {
                        $query->where('business_field_id', $businessFieldId);
                    })
                    ->select('name', 'type')
                    ->get()
                    ->groupBy('type')
                    ->map(function ($group) {
                        return $group->map(function ($item) {
                            return [
                                'name' => $item->name,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        });
                    })->toArray();

                foreach ($seedsByBusinessField as $seedType => $seedsArray) {
                    $model = config('util.SEED_TYPES.' . $seedType);
                    (new $model)->query()->insert($seedsArray);
                }

                (new LocationController())->createLocationAndInventory([
                    'name' => $this->business_name,
                    'tel' => $user->tel,
                    'email' => $user->email,
                    'status' => 1,
                    'address_detail' => $this->address,
                    'is_main' => 1,
                ]);

                $userCreate = \App\Models\Tenant\User::query()->create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username ?? '',
                ]);

                $userCreate->roles()->attach(Role::query()->where('name', 'super-admin')->first()->id);

            }

        } catch (\Throwable $th) {

            throw $th;

        }
    }
}
