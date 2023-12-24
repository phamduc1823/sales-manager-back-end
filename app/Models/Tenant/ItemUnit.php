<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class ItemUnit extends Model
{
    use HasFactory, UsesTenantConnection;
    public $table = 'item_units';

    protected $fillable = ['name'];
}
