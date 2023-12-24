<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\GroupCustomerRequest;
use App\Models\Tenant\Customer;
use App\Models\Tenant\GroupCustomer;

class GroupCustomerController extends Controller
{
    public function __construct(
        private GroupCustomer $model,
        private Customer $customerModel,
        private GroupCustomerRequest $request
    )
    {
    }

    public function list(){
        try {
            return responseApi($this->model::query()
                ->orderBy('id','desc')
                ->where('type',0)
                ->get(), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function getListCustomer()
    {
        try {
            $query = Customer::with(['province', 'district', 'commune'])->whereType(0)->paginate(10);
            $return = $query->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'tel' => $data->tel,
                    'email' => $data->email,
                    'address' => $data->commune->name . ', ' . $data->district->name . ', ' . $data->province->name
                ];
            });
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }
    public function store(){
        try {
            $this->model::create($this->request->all());
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
            $this->customerModel::query()
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
