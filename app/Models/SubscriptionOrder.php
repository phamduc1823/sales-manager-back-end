<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class SubscriptionOrder extends Model
{
    use HasFactory, UsesLandlordConnection;

    protected $table = 'subscription_orders';
    protected $fillable = [
        'tenant_id',
        'pricing_id',
        'type',
        'name',
        'tel',
        'assigned_to',
        'status'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }
    public function pricing()
    {
        return $this->belongsTo(Pricing::class, 'pricing_id', 'id');
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }
}
