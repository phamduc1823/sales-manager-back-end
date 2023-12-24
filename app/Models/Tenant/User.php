<?php

namespace App\Models\Tenant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UsesTenantConnection, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class,'created_by','id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class,'created_by','id');
    }
    public function orderReturns()
    {
        return $this->hasMany(OrderReturn::class,'created_by','id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'created_by','id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * A model may have multiple roles.
     */
//    public function roles (): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(
//            config('permission.models.role'), //This model uses the LandlordConnection
//            Tenant::current()->getDatabaseName().'.'.config('permission.table_names.model_has_roles'),  //Just inserted the tenant DB name here
//            'role_id',
//            'model_id'
//        );
//    }

    /**
     * A model may have multiple direct permissions.
     */
//    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->morphToMany(
//            config('permission.models.permission'),
//            'model',
//                Tenant::current()->getDatabaseName().'.'.config('permission.table_names.model_has_permissions'),
//            config('permission.column_names.model_morph_key'),
//            'permission_id'
//        );
//    }
}
