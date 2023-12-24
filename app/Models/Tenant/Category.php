<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Category extends Model
{
    use HasFactory, UsesTenantConnection;
    public $table = 'categories';

    protected $fillable = ['name'];
}
