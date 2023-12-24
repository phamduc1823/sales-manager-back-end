<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Inventory extends Model
{
    use HasFactory, UsesTenantConnection;
    public $table = 'inventories';
    protected $fillable = [
        "location_id",
        "name",
        "code",
        "status",
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'inventory_id', 'id');
    }
    public function inventoryTransactionsOut()
    {
        return $this->hasMany(InventoryTransaction::class, 'inventory_id_out', 'id');
    }
}
