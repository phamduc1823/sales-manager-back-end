<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class ProductGallery extends Model
{
    use HasFactory, UsesTenantConnection;
    public $table = 'product_galleries';
    protected $fillable = [
        "product_id",
        "image",
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
