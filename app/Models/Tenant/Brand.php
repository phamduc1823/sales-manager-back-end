<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Brand extends Model
{
    use HasFactory, UsesTenantConnection;

    public $table = 'brands';
    protected $fillable = ['name'];
}
