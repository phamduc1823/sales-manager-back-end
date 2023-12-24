<?php

namespace App\Models\Address;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $connection = "landlord";
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'province_id'];
    protected $hidden = ['created_at', 'updated_at'];
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
    public function communes()
    {
        return $this->hasMany(Commune::class, 'district_id', 'id');
    }
    public function locations(){
        return $this->hasMany(Location::class, 'district_code');
    }
    public function customers(){
        return $this->hasMany(Customer::class, 'district_code');
    }

}
