<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class OrderReturnProduct extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'order_return_products';
    protected $fillable=[
        "order_return_id",
        "type",
        "variant_id",
        "batch_id",
        "price",
        "quantity",
        "total",
    ];
    protected $casts = [
        'type' => 'boolean',
    ];
    public function batch()
    {
        return $this->belongsTo(Batch::class,'batch_id','id');
    }
    public function variant()
    {
        return $this->belongsTo(Variant::class,'variant_id','id');
    }
    public function orderReturn()
    {
        return $this->belongsTo(OrderReturn::class,'order_return_id','id');
    }
}
