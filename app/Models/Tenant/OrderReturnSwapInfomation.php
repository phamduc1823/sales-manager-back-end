<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class OrderReturnSwapInfomation extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'order_return_swap_infomations';
    protected $fillable=[
        "order_return_id",
        "swap_total_price",
        "total_discount",
        "tax",
        "discount _type",
        "service_charge",
    ];
    public function orderReturn()
    {
        return $this->belongsTo(OrderReturn::class,'order_return_id','id');
    }
}
