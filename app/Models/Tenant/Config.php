<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Config extends Model
{
    use HasFactory, UsesTenantConnection;

    public $table = "configs";
    protected $fillable = [
        "business_name",
        "tel",
        "email",
        "business_field_code",
        "business_type",
        "business_registration",
        "license_date",
        "license_address",
        "province_code",
        "district_code",
        "ward_code",
        "address_detail",
        "logo",
    ];
}
