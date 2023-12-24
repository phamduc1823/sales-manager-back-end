<?php

namespace App\Models\Tenant;

use App\Models\Address\Commune;
use App\Models\Address\District;
use App\Models\Address\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Location extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'locations';
    protected $fillable=[
        "name",
        "image",
        "description",
        "tel",
        "email",
        "province_code",
        "district_code",
        "ward_code",
        "address_detail",
        "status",
        "is_main",
        "created_by"
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'is_main' => 'boolean',
    ];
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function inventories(){
        return $this->hasMany(Inventory::class,'location_id');
    }
    public function province(){
        return $this->belongsTo(Province::class,'province_code');
    }
    public function district(){
        return $this->belongsTo(District::class,'district_code');
    }
    public function commune(){
        return $this->belongsTo(Commune::class,'ward_code');
    }
    public function debts(){
        return $this->hasMany(Debt::class,'location_id','id');
    }
}
