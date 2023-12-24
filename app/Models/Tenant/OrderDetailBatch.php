<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class OrderDetailBatch extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'order_detail_batches';
    protected $fillable=[
        "order_detail_id",
        "batch_id",
        "quantity"
    ];
    public function batch()
    {
        return $this->belongsTo(Batch::class,'batch_id','id');
    }
    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class,'order_detail_id','id');
    }
}
