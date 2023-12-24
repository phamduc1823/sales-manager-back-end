<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class VariationQuantity extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'variation_quantities';
    protected $fillable=[
        "variation_id",
        "inventory_id",
        "batch_id",
        "price_import",
        "quantity"
    ];
    public function variation()
    {
        return $this->belongsTo(Variation::class,'variation_id','id');
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class,'inventory_id','id');
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class,'batch_id','id');
    }
    public function inventoryTransactionDetail()
    {
        return $this->belongsTo(InventoryTransactionDetail::class,'variation_id','variation_id');
    }

}
