<?php

namespace App\Multitenancy\Tasks;

use App\Models\Tenant;
use App\Models\Tenant\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\PersonalAccessToken as BasePersonalAccessToken;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SetSanctumPersonAccessTokenModelTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant|\Spatie\Multitenancy\Models\Tenant $tenant): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    public function forgetCurrent(): void
    {
        Sanctum::usePersonalAccessTokenModel(BasePersonalAccessToken::class);
    }
}
