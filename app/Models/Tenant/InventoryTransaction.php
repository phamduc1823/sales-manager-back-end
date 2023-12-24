<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
class InventoryTransaction extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'inventory_transactions';
    protected $fillable=[
        "inventory_id",
        "inventory_id_out",
        "partner_id",
        "partner_type",
        "trans_type",
        "inventory_transaction_id",
        "reason",
        "note",
        "status",
        "created_by"
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
//    protected $casts = [
//        'status' => 'boolean',
//    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class,'inventory_id','id');
    }
    public function inventoryOut()
    {
        return $this->belongsTo(Inventory::class,'inventory_id_out','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function partner()
    {
        return $this->belongsTo(Customer::class,'partner_id','id');
    }
    public function inventoryTransactionDetails()
    {
        return $this->hasMany(InventoryTransactionDetail::class,'inventory_transaction_id','inventory_transaction_id');
    }
}
