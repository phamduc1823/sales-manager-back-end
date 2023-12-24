<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Batch extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'batches';
    protected $fillable=[
        "code",
        "variation_id",
        "manufacture_date",
        "expiration_date",
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'manufacture_date' => 'date:d-m-Y',
        'expiration_date' => 'date:d-m-Y',
    ];
    public function variation(){
        return $this->belongsTo(Variation::class,'variation_id');
    }
    public function orderDetailBatch()
    {
        return $this->hasMany(OrderDetailBatch::class,'order_detail_id','id');
    }
}
