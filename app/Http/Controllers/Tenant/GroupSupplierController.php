<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\GroupSupplierRequest;
use App\Models\Tenant\Customer;
use App\Models\Tenant\GroupCustomer;

class GroupSupplierController extends Controller
{
    public function __construct(
        private GroupCustomer $model,
        private Customer $supplierModel,
        private GroupSupplierRequest $request
    )
    {
    }

    public function list(){
        try {
            return responseApi($this->model
                ->query()
                ->where('type', 1)
                ->orderBy('id','desc')
                ->get(), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store(){
        try {
            $this->model::create([
                ...$this->request->all(),
                'type' => 1
            ]);
            return responseApi("Tạo thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            return responseApi($this->model::find($this->request->id), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            $this->model::find($this->request->id)->update($this->request->all());
            return responseApi("Cập nhật thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete(){
        try {
            $this->supplierModel::query()
                ->where('group_customer_id', $this->request->id)
                ->update(['group_customer_id' => null]);
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
