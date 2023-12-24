<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PrintedForm extends Model
{
    use HasFactory, UsesTenantConnection;

    public $table = 'printed_forms';
    protected $fillable = [
        "name",
        "form",
        "default"
    ];
}
