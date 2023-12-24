<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class VariationAttribute extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = "variation_attributes";

    protected $fillable = [
        "variation_id",
        "attribute_value_id"
    ];
}
