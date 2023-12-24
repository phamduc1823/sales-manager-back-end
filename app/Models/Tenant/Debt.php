<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Debt extends Model
{
    use HasFactory, UsesTenantConnection;

    public $table = "debts";

    protected $fillable = [
        "partner_id",
        "partner_type",
        "debit_at",
        "due_at",
        "type",
        "name",
        "amount_debt",
        "amount_paid",
        "note",
        "status",
        "location_id",
    ];

    public function partner()
    {
        return $this->belongsTo(Customer::class, 'partner_id', 'id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
