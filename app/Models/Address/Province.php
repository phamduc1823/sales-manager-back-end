<?php

namespace App\Models\Address;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';
    protected $connection = "landlord";
    protected $primaryKey = 'id';
    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id', 'id');
    }
    public function locations(){
        return $this->hasMany(Location::class, 'province_code');
    }
    public function customers(){
        return $this->hasMany(Customer::class, 'province_code');
    }
}
