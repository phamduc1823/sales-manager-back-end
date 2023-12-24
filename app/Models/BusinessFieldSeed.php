<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class BusinessFieldSeed extends Model
{
    use HasFactory,UsesLandlordConnection;
    protected $table = 'business_field_seeds';
    protected $fillable = ['business_field_id', 'seed_id'];
    public function businessField()
    {
        return $this->belongsTo(BusinessField::class, 'business_field_id', 'id');
    }
    public function seed()
    {
        return $this->belongsTo(Seed::class, 'seed_id', 'id');
    }
}
