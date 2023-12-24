<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //

    public function list(Request $request)
    {
        try {
            $user = $request->user();
            $role_id = $user->roles->first()->id;
            $roles = Role::query()->where('id', '>', $role_id)->get();
            return responseApi($roles, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }
}
