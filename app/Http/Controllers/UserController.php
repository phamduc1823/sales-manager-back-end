<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(
        protected UserRequest $request
    )
    {
    }

    public function index()
    {
        $role = Role::all();
        $user = request()->user();
        $users = User::with(['tenants', 'parent'])->where('id', '!=', $user->id)->orderBy('created_at', 'desc')->get();
        return view('admin.user.index', compact('users', 'role'));
    }

    public function show()
    {
        try {
            if ($this->request->show_id) {
                $user = User::with(['tenants.business_field', 'parent'])->find($this->request->show_id);
            } elseif ($this->request->update_id) {
                $user = User::query()->with('roles:id,name')->whereId($this->request->update_id)->get();
                $user = collect($user->map(function ($item) {
                    return [
                        "id" => $item->id,
                        "name" => $item->name,
                        "email" => $item->email,
                        "tel" => $item->tel,
                        "status" => $item->status,
                        "role" => $item->roles[0]->id??null,
                    ];
                }))->collapse();
            }
            if (!$user) return responseApi("không tồn tại!");
            return responseApi($user, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete()
    {
        try {
            $tenant = User::query()->findOrFail($this->request->id);
            $tenant?->tenants()->delete();
            $tenant?->delete();
            return responseApi('Xoá thành công', true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function trash()
    {
        $users = User::with(['tenants'])->onlyTrashed()->get();
        return view('admin.user.trash', compact('users'));
    }

    public function restore()
    {
        $user = User::whereId($this->request->id);
        $user?->restore();
        Tenant::withTrashed()->where('user_id', $this->request->id)->restore();
        return responseApi('Thành công', true);
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $this->request->ten_user,
                'email' => $this->request->email_user,
                'password' => password_hash($this->request->password, PASSWORD_DEFAULT),
                'tel' => $this->request->phone_number ? $this->request->phone_number : null,
            ]);
            $user->roles()->attach($this->request->role);
            DB::commit();
            return responseApi("Tạo thành công", true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        DB::beginTransaction();
        try {
            $user = User::query()->find($this->request->id);
            if (!$user) return responseApi('User không tồn tại');
            $user->name = $this->request->ten_user;
            $user->email = $this->request->email_user;
            if($this->request->password) $user->password = bcrypt($this->request->password);
            $user->tel = $this->request->phone_number ?? null;
            $user->save();
            $user->roles()->sync($this->request->role);
            DB::commit();
            return responseApi("Cập nhật thành công", true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi("Cập nhật thất bại");
        }
    }
}
