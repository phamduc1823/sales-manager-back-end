<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Models\BusinessField;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Pricing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function __construct(
        protected TenantRequest $request
    )
    {
    }

    public function index()
    {
        $tenants = Tenant::with(['user', 'business_field', 'pricing'])->orderBy('created_at', 'desc')->get();
        $businessField = BusinessField::all();
        $users = User::all();
        $pricing = Pricing::all();
        return view('admin.tenant.index', compact('tenants', 'businessField', 'users', 'pricing'));
    }

    public function store()
    {

        DB::beginTransaction();
        try {
            $tenantName = cleanText($this->request->input('name_tenant'));
            $filterDatabase = Tenant::where('database', $tenantName)->get();

            $business_field_id = $this->request->input('business_field');

            if ($business_field_code = $this->request->input('business_code')) {

                $business_field = BusinessField::where('code', $business_field_code)->first();

                if (!$business_field) return responseApi('Lĩnh vực kinh doanh không tồn tại');

                $business_field_id = $business_field->id;
            }

            if (count($filterDatabase) > 0) {
                return responseApi('Cơ sở đã tổn tại');
            } else {
                $due_at = Pricing::where('id', $this->request->pricing_id)->first()?->expiry_day;
                $tenant = new Tenant();
                $tenant->business_name = $this->request->input('business_name');
                $tenant->address = $this->request->input('address');
                $tenant->name = $tenantName;
                $tenant->domain = env('APP_URL') . '/' . $tenantName;
                $tenant->database = $tenantName;
                $tenant->user_id = $this->request->input('user_id');
                $tenant->business_field_id = $business_field_id;
                $tenant->pricing_id = $this->request->pricing_id;
                $tenant->due_at = Carbon::now()->addDays($due_at)->format('Y-m-d');
                $tenant->status = 1;
                $tenant->save();
                return responseApi('Tạo chi nhánh thành công', true);
            }
            DB::commit();
            return responseApi("Tạo thất bại", true);

        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage());
        }
    }

    public function getByUser()
    {
        try {
            return responseApi(\auth()->user()->tenants()->select('name', 'business_name')->get(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function getTenant()
    {
        try {
            return responseApi(Tenant::current(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
