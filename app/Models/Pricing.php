<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
class Pricing extends Model
{
    use HasFactory,UsesLandlordConnection;
    protected $table = 'pricings';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'max_locations', 'max_users', 'price',"expiry_day"];

    public function users()
    {
        return $this->hasMany(User::class, 'pricing_id', 'id');
    }
    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'pricing_id', 'id');
    }

    public function scopeStatistic($query)
    {
        return $query->withCount('tenants')->get()->pluck('tenants_count', 'name');
    }
}
