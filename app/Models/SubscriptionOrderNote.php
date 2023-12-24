<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class SubscriptionOrderNote extends Model
{
    use HasFactory, UsesLandlordConnection;

    protected $table = 'subscription_order_notes';
    protected $fillable = [
        'subscription_order_id',
        'note',
        'created_by'
    ];

    public function subscriptionOrder()
    {
        return $this->belongsTo(SubscriptionOrder::class, 'subscription_order_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
