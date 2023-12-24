<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Attribute extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = "attributes";

    protected $fillable = [
        "product_id",
        "name"
    ];

    public function attributeValues() {
        return $this->hasMany(AttributeValue::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function variationAttributes() {
        return $this->hasMany(VariationAttribute::class);
    }
}
