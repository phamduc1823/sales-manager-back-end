<?php

namespace App\Models\Address;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;
    protected $table = 'communes';
    protected $connection = "landlord";
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'district_id'];
    protected $hidden = ['created_at', 'updated_at'];
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
    public function locations(){
        return $this->hasMany(Location::class,'ward_code');
    }
    public function customers(){
        return $this->hasMany(Customer::class,'ward_code');
    }

}
