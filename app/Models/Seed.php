<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Seed extends Model
{
    use HasFactory,UsesLandlordConnection;
    protected $table = 'seeds';
    protected $fillable = ['name', 'type'];
    public function businessFieldSeed()
    {
        return $this->belongsToMany(BusinessField::class, 'business_field_seeds', 'seed_id', 'business_field_id');
    }
}
