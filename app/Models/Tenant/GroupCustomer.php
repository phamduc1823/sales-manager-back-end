<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class GroupCustomer extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = "group_customers";

    protected $fillable = [
        "name",
        "description",
        "type",
    ];
}
