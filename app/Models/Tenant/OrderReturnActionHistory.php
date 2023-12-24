<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class OrderReturnActionHistory extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'order_return_action_histories';
    protected $fillable=[
        "order_return_id",
        "action",
        "performed_by"
    ];
    public function orderReturn()
    {
        return $this->belongsTo(OrderReturn::class,'order_return_id','id');
    }
}
