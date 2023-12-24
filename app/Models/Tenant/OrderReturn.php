<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class OrderReturn extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'order_returns';
    protected $fillable=[
        "order_id",
        "created_by",
        "customer_id",
        "reason",
        "swap",
        "has_returned",
        "return_total_price",
        "payment_by_type",
        "return_charge",
        "has_payed",
        "status"
    ];
    protected $casts = [
        'swap' => 'boolean',
        'has_returned' => 'boolean',
        'payment_by_type' => 'boolean',
        'status' => 'boolean',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function orderReturnProducts()
    {
        return $this->hasMany(OrderReturnProduct::class,'order_return_id','id');
    }
    public function orderReturnActionHistories()
    {
        return $this->hasMany(OrderReturnActionHistory::class,'order_return_id','id');
    }
    public function orderReturnSwapInfomation()
    {
        return $this->hasOne(OrderReturnSwapInfomation::class,'order_return_id','id');
    }
    public function payments(){
        return $this->morphMany(Payment::class, 'paymentFor');
    }
}
