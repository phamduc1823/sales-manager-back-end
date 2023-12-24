<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\Pricing;
use App\Http\Requests\PricingRequest;

class PricingController extends Controller
{
    public function __construct(
        private Pricing        $model,
        private PricingRequest $request,
        private string         $module_name = "Pricing",
    )
    {
    }

    public function index()
    {
        $pricings = Pricing::all();
        return view('admin.pricing.index', compact('pricings'));
    }

    public function store()
    {
        try {
            if (!empty($this->request->validated())) {
                $this->model::create($this->request->except('_token', 'id'));
                return responseApi("Tạo thành công!", true);
            }
            return responseApi("Tạo thất bại!", false);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            if (!$this->model::find($this->request->id)) return responseApi($this->module_name . " không tồn tại!", false);
            return responseApi($this->model::find($this->request->id), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            if (!$this->model::find($this->request->id)) return responseApi($this->module_name . " không tồn tại!", false);
            if (!empty($this->request->validated())) {
                $category = $this->model::find($this->request->id);
                $category->update($this->request->except('_token'));
                return responseApi("Cập nhật thành công!", true);
            }
            return responseApi("Cập nhật thất bại!", false);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete()
    {
        try {
            if (!$this->model::find($this->request->id)) return responseApi($this->module_name . " không tồn tại!", false);

            $this->model::find($this->request->id)->delete();

            return responseApi("Xóa thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function indexApi()
    {
        try {
            $pricings = $this->model->query()
                ->select([
                    'id',
                    'name',
                    'max_locations',
                    'max_users',
                    'price',
                ])
                ->get();
            return responseApi($pricings, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function getByTenant(Request $request)
    {
        try {
            $tenant = Tenant::current();
            $pricing = $this->model->query()
                ->select([
                    'id',
                    'name',
                    'max_locations',
                    'max_users',
                    'price',
                    'expiry_day'
                ])
                ->where('id', $tenant->pricing_id)
                ->first();
            return responseApi($pricing, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
