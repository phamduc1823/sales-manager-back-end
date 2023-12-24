<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class InventoryTransactionDetail extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'inventory_transaction_details';
    protected $fillable=[
        "inventory_transaction_id",
        "variation_id",
        "batch_id",
        "price",
        "price_type",
        "quantity"
    ];

    public function inventoryTransaction()
    {
        return $this->belongsTo(InventoryTransaction::class,'inventory_transaction_id','inventory_transaction_id');
    }
    public function variation()
    {
        return $this->belongsTo(Variation::class,'variation_id','id');
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class,'batch_id','id');
    }
    public function variationQuantity()
    {
        return $this->hasOne(VariationQuantity::class,'variation_id','variation_id');
    }
}
