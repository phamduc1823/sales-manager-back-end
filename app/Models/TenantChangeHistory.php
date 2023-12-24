<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class TenantChangeHistory extends Model
{
    use HasFactory, UsesLandlordConnection;

    protected $table = 'tenant_change_histories';
    protected $fillable = [
        'tenant_id',
        'change_type',
        'from_pricing_id',
        'to_pricing_id',
        'total_price',
        'created_by',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }
    public function fromPricing()
    {
        return $this->belongsTo(Pricing::class, 'from_pricing_id', 'id');
    }
    public function toPricing()
    {
        return $this->belongsTo(Pricing::class, 'to_pricing_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function order()
    {
        return $this->hasMany(Order::class, 'tenant_change_history_id', 'id');
    }
}
