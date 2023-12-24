<?php

namespace App\Models\Tenant;

use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PersonalAccessToken extends \Laravel\Sanctum\PersonalAccessToken
{
    use UsesTenantConnection;
}
