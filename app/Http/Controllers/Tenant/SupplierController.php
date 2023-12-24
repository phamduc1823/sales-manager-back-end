<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SupplierRequest;
use App\Models\Tenant\Customer;

class SupplierController extends Controller
{
    public function __construct(
        private Customer $model,
        private SupplierRequest $request,
    )
    {
    }

    public function list(){
        try {
            $supplierData = $this->model::with(['group_customer' => function ($query) {
                $query->where('type', 1);
            }])
                ->where('name', 'like', "%".$this->request->q."%")
                ->where('type', 1)
                ->orderBy('id', 'desc')
                ->get();

            $data = $supplierData->map(function ($supplierData){
                return [
                    'id' => $supplierData->id,
                    'group_customer_id' => $supplierData->group_customer_id??null,
                    'group_customer_name' => $supplierData->group_customer->name??null,
                    'type' => $supplierData->type,
                    'name' => $supplierData->name,
                    'gender' => $supplierData->gender??null,
                    'dob' => $supplierData->dob??null,
                    'email' => $supplierData->email??null,
                    'tel' => $supplierData->tel??null,
                    'status' => $supplierData->status,
                    'province_code' => $supplierData->province_code??null,
                    'district_code' => $supplierData->district_code??null,
                    'ward_code' => $supplierData->ward_code??null,
                    'address_detail' => $supplierData->address_detail??null,
                    'note' => $supplierData->note??null,
                    'created_at' => $supplierData->created_at,
                    'updated_at' => $supplierData->updated_at,
                ];
            });

            return responseApi($data, true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
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
            $supplierData = $this->model::with(['group_customer' => function ($query) {
                $query->where('type', 1);
            }])
                ->where('type', 1)
                ->where('id', $this->request->id)->get();

            $data = $supplierData->map(function ($supplierData) {
                return [
                    'id' => $supplierData->id,
                    'group_customer_id' => $supplierData->group_customer_id??null,
                    'group_customer_name' => $supplierData->group_customer->name??null,
                    'type' => $supplierData->type,
                    'name' => $supplierData->name,
                    'gender' => $supplierData->gender??null,
                    'dob' => $supplierData->dob??null,
                    'email' => $supplierData->email??null,
                    'tel' => $supplierData->tel??null,
                    'status' => $supplierData->status,
                    'province_code' => $supplierData->province_code??null,
                    'district_code' => $supplierData->district_code??null,
                    'ward_code' => $supplierData->ward_code??null,
                    'address_detail' => $supplierData->address_detail??null,
                    'note' => $supplierData->note??null,
                    'created_at' => $supplierData->created_at,
                    'updated_at' => $supplierData->updated_at,
                ];
            });

            return responseApi(collect($data)->collapse(), true);
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
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
