<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Product extends Model
{
    use HasFactory, UsesTenantConnection;
    public $table = 'products';

    protected $fillable = [
        'name',
        'image',
        'weight',
        'description',
        'manage_type',
        'brand_id',
        'warranty_id',
        'item_unit_id',
        'category_id',
        'status'
    ];

    public function attributes() {
        return $this->hasMany(Attribute::class);
    }

    public function variations() {
        return $this->hasMany(Variation::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
    public function warranty()
    {
        return $this->belongsTo(Warranty::class, 'warranty_id', 'id');
    }
    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class, 'item_unit_id', 'id');
    }
    public function productGalleries()
    {
        return $this->hasMany(ProductGallery::class, 'product_id', 'id');
    }
}
