<?php

namespace App\Multitenancy\Tasks;

use App\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SwitchPackagesTask implements SwitchTenantTask
{
    protected ?string $originalSpatiePermissionCacheKey;

    public function makeCurrent(Tenant|\Spatie\Multitenancy\Models\Tenant $tenant): void
    {
//        $this->originalSpatiePermissionCacheKey = PermissionRegistrar::$cacheKey;
        $this->setup($tenant);
    }

    private function setup(Tenant $tenant)
    {
//        PermissionRegistrar::$cacheKey = 'spatie.permission.cache.tenant.' . $tenant->id;
//        config()->set('permission.cache.key', 'spatie.permission.cache.tenant.' . $tenant->id);
        config()->set('permission.models.role', \App\Models\Tenant\Role::class);
        config()->set('permission.models.permission', \App\Models\Tenant\Permission::class);
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function forgetCurrent(): void
    {
        // PermissionRegistrar::$cacheKey = $this->originalSpatiePermissionCacheKey;

        config()->set('permission.models.role', Role::class);
        config()->set('permission.models.permission', Permission::class);
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
