<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{

    function getUserEnterprise(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return responseApi($validator->errors(), false);
        }
        if (!Auth::guard('web')->attempt($credentials) && !Auth::guard('web')->user()?->status == 1) {
            $msg = "Thông tin email hoặc mật khẩu không chính xác";
            return responseApi(['password' => $msg], false);
        }
        /* ------------ Create a new personal access token for the user. ------------ */
        $user = Auth::guard('web')->user();
        $token = $user->createToken('authToken');
        $data = generateUserToken($user);
        return responseApi($data, true);
    }

    public function loginEnterprise(Request $request)
    {
        $user = \auth()->user();

        $tenant = Tenant::with('pricing')->where('name', $request->tenant_name)->firstOrFail();


        if (Carbon::now()->greaterThan(Carbon::make($tenant->due_at))) {
            return responseApi(['domain_name' => "Cửa hàng đã hết hạn sử dụng, vui lòng thay đổi gói hoặc gia hạn"], false);
        }

        $tenant->makeCurrent();

        $tenantUser = Tenant\User::query()->with('roles')->where('email', $user->email)->first();

        $token = generateUserToken($tenantUser);

        $location = Tenant\Location::query()->first();

        $inventory = Tenant\Inventory::query()->where('location_id', $location->id)->first();

        $menus = getTenantMenus();

        $data = [
            'user' => $tenantUser,
            'token' => $token,
            'location' => $location ?? null,
            'inventory' => $inventory ?? null,
            'tenant' => $tenant ?? null,
            'menus' => $menus ?? null,
        ];

        return responseApi($data, true);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return responseApi($validator->errors(), false);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $user->assignRole('customer');
        $data = generateUserToken($user);
        return responseApi($data, true);
    }

    public function login(Request $request)
    {
        if (!Tenant::checkCurrent()) {
            return responseApi(['domain_name' => "Địa chỉ doanh nghiệp không tồn tại"], false);
        }

        if (Carbon::now()->greaterThan(Carbon::make(Tenant::current()->due_at))) {
            return responseApi(['domain_name' => "Đã hết hạn sử dụng"], false);
        }


        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);
        if ($validator->fails()) {
            return responseApi($validator->errors(), false);
        }
        if (!Auth::guard('api')->attempt($credentials)) {
            $msg = "Thông tin email hoặc mật khẩu không chính xác";
            return responseApi(['password' => $msg], false);
        }
        /* ------------ Create a new personal access token for the user. ------------ */
        $user = Auth::guard('api')->user();

        if ($user->status == 0) {
            return responseApi(['domain_name' => "Tài khoản của bạn đã bị khóa, vui lòng liên hệ quản trị viên"], false);
        }

        $user->load('roles');

        $role = $user->roles->first()->name;

        $token = generateUserToken($user);

        $location = $user->location_id
            ? Tenant\Location::query()->find($user->location_id)
            : Tenant\Location::query()->first()->id;

        $inventory = Tenant\Inventory::query()->where('location_id', $location->id)->first();

        $data = [
            'user' => $user,
            'token' => $token,
            'location' => $location ?? null,
            'inventory' => $inventory ?? null,
            'tenant' => Tenant::current()->load('pricing') ?? null,
            'menus' => getTenantMenus($role) ?? null,
        ];
        return responseApi($data, true);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

}
