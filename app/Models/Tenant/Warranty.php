<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Warranty extends Model
{
    use HasFactory, UsesTenantConnection;

    public $table = "warranties";

    protected $fillable = [
        "name",
        "unit",
        "period"
    ];
}
