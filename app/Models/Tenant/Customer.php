<?php

namespace App\Models\Tenant;

use App\Models\Address\Commune;
use App\Models\Address\District;
use App\Models\Address\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Customer extends Model
{
    use HasFactory, UsesTenantConnection;

    public $table = "customers";

    protected $fillable = [
        "group_customer_id",
        "type",
        "name",
        "gender",
        "dob",
        "email",
        "tel",
        "status",
        "province_code",
        "district_code",
        "ward_code",
        "address_detail",
        "note",
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    function group_customer () {
        return $this->belongsTo(GroupCustomer::class, 'group_customer_id');
    }
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class,'partner_id','id');
    }
   public function debts()
    {
        return $this->hasMany(Debt::class,'partner_id','id');
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
    public function order(){
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
