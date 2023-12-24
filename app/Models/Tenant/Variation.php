<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Variation extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'variations';
    protected $fillable=[
        "product_id",
        "sku",
        "barcode",
        "variation_name",
        "display_name",
        "image",
        "price_import",
        "price_export",
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function attributeValues() {
        return $this->belongsToMany(AttributeValue::class, 'variation_attributes')
            ->withPivot('id');
    }

    public function variationAttributes()
    {
        return $this->hasMany(VariationAttribute::class);
    }

    public function variationQuantities()
    {
        return $this->hasMany(VariationQuantity::class,'variation_id','id');
    }

    public function inventoryTransactionDetails()
    {
        return $this->hasMany(InventoryTransactionDetail::class,'variation_id','variation_id');

    }
    public function batchs(){
        return $this->hasMany(Batch::class,'variation_id');
    }

    public function productName(){
        return $this->hasMany(Product::class,'id', 'product_id');
    }
}
