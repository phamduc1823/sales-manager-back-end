<?php

namespace App\Multitenancy\Finders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class NameTenantFinder extends TenantFinder
{
    use UsesTenantModel;

    public function findForRequest(Request $request): ?Tenant
    {

        $domain_name = $request->domain_name;

        return $this->getTenantModel()::whereName($domain_name)->first();

    }
}
