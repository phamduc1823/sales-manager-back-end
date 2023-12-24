<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Role;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use App\Http\Requests\Tenant\UserRequest;

class UserController extends Controller
{

    public function __construct(
        private User          $model,
        protected UserRequest $request
    )
    {
    }

    public function list()
    {
        try {
            $user = request()->user();
            $userQuery = $this->model::with(['location', 'roles'])
                ->where('id', '!=', $user->id)
                ->when($this->request->location_id, function ($query) {
                    return $query->where('location_id', $this->request->location_id);
                });
            if ($user->hasRole('admin')) {
                $userQuery->where('location_id', $user->location_id);
            }
            $users = $userQuery->orderBy('id', 'desc')->get();
            return responseApi($users, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function show()
    {
        try {
            return responseApi($this->model::with('roles')->find($this->request->id), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store()
    {
        try {
            $user = new User();
            $user->name = $this->request->name;
            $user->email = $this->request->email;
            $user->email_verified_at = now();
            $user->password = password_hash($this->request->password, PASSWORD_DEFAULT);
            $user->location_id = $this->request->location_id;
            $user->username = !$this->request->username ? null : $this->request->username;
            $user->tel = !$this->request->tel ? null : $this->request->tel;
            $user->status = !$this->request->status ? 0 : $this->request->status;
            $user->created_by = $this->request->user()->id ?? null;
            $user->save();
            $user->roles()->attach($this->request->role_id);
            return responseApi('Tạo tài khoản admin thành công', true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            $user = $this->model::query()->findOrFail($this->request->id);
            $user->name = $this->request->name;
            $user->email = $this->request->email;
            $user->email_verified_at = now();
            if ($this->request->password) {
                $user->password = password_hash($this->request->password, PASSWORD_DEFAULT);
            }
            $user->location_id = $this->request->location_id;
            $user->username = !$this->request->username ? null : $this->request->username;
            $user->tel = !$this->request->tel ? null : $this->request->tel;
            $user->status = !$this->request->status ? 0 : $this->request->status;
            $user->save();
            if (isset($this->request->role_id)) {
                $role=Role::find($this->request->role_id)->name;
                $user->syncRoles($role);
            }
            return responseApi('Cập nhật tài khoản thành công', true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete()
    {
        try {
            $user = $this->model::query()->findOrFail($this->request->id);
            $user?->removeRole($user->getRoleNames()[0])->delete();
            return responseApi('Xóa tài khoản thành công', true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
