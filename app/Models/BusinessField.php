<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class BusinessField extends Model
{
    use HasFactory;

    protected $table = 'business_fields';
    protected $fillable = [
        'name',
        'code',
        'detail'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function tenants(){
        return $this->hasMany(Tenant::class,'business_field_id');
    }
    public function BusinessFieldSeed(){
        return $this->belongsToMany(BusinessFieldSeed::class,'business_field_seeds','business_field_id','seed_id');
    }
    public function scopeStatistic($query)
    {
        return $query->withCount('tenants')->get()->pluck('tenants_count', 'name');
    }
}
