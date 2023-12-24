<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class AttributeValue extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = "attribute_values";

    protected $fillable = [
        "attribute_id",
        "value"
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValuesVariation()
    {
        return $this->belongsToMany(Variation::class, 'variation_quantities', 'attribute_value_id', 'variation_id');

    }
}
