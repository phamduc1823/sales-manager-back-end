<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CustomerRequest;
use App\Models\Tenant\Customer;

class CustomerController extends Controller
{
    public function __construct(
        private Customer        $model,
        private CustomerRequest $request
    )
    {
    }

    public function list()
    {
        try {
            $customerData = $this->model::with(['group_customer' => function ($query) {
                $query->where('type', 0);
            }])
                ->where('type', '<>', 1)
                ->orderBy('id', 'desc')
                ->get();
            $data = $customerData->map(function ($customerData) {
                return [
                    'id' => $customerData->id,
                    'group_customer_id' => $customerData->group_customer_id ?? null,
                    'group_customer_name' => $customerData->group_customer->name ?? null,
                    'type' => $customerData->type,
                    'name' => $customerData->name,
                    'gender' => $customerData->gender ?? null,
                    'dob' => $customerData->dob ?? null,
                    'email' => $customerData->email ?? null,
                    'tel' => $customerData->tel ?? null,
                    'status' => $customerData->status,
                    'province_code' => $customerData->province_code ?? null,
                    'district_code' => $customerData->district_code ?? null,
                    'ward_code' => $customerData->ward_code ?? null,
                    'address_detail' => $customerData->address_detail ?? null,
                    'note' => $customerData->note ?? null,
                    'created_at' => $customerData->created_at,
                    'updated_at' => $customerData->updated_at,
                ];
            });

            return responseApi($data, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function getListCustomer()
    {
        try {
            $query = Customer::query()->where('status',1)->get();
            $return = $query->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'tel' => $data->tel,
                    'type'=> $data->type,
                    'name_tel' => $data->name . ' - ' . $data->tel,
                    'email' => $data->email,
                    'status' => $data->status,
                    'address' => $data->address_detail,
                    'created_at' => $data->created_at->format('H:i d-m-Y'),
                    'updated_at' => $data->created_at->format('H:i d-m-Y'),
                ];
            });
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function getCustomerWithStatus()
    {
        try {
            $query = Customer::query()->whereStatus(1)->get();
            $data = $query->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'tel' => $data->tel,
                    'email' => $data->email,
                    'status' => $data->status,
                    'address' => $data->address_detail,
                    'created_at' => $data->created_at->format('H:i d-m-Y'),
                    'updated_at' => $data->created_at->format('H:i d-m-Y'),
                ];
            });
            return responseApi($data, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function searchCustomer()
    {
        try {
            $name = $this->request->name;
            $tel = $this->request->tel;
            $customers = Customer::query()
                ->where('name', 'like', '%' . $name . '%')
                ->orWhere('tel', 'like', '%' . $tel . '%')
                ->get();
            return responseApi($customers, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function store()
    {
        try {
            $customer = $this->model::create($this->request->all());
            if ($this->request->statusCreate == 1) {
                return $this->list();
            } else {
                return responseApi($customer, true);
            }
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            $customerData = $this->model::with(['group_customer'])->where('id', $this->request->id)->get();

            $data = $customerData->map(function ($customerData) {
                return [
                    'id' => $customerData->id,
                    'group_customer_id' => $customerData->group_customer_id ?? null,
                    'group_customer_name' => $customerData->group_customer?->name ?? null,
                    'type' => $customerData->type,
                    'name' => $customerData->name,
                    'gender' => $customerData->gender ?? null,
                    'dob' => $customerData->dob ?? null,
                    'email' => $customerData->email ?? null,
                    'tel' => $customerData->tel ?? null,
                    'status' => $customerData->status,
                    'province_code' => $customerData->province_code ?? null,
                    'district_code' => $customerData->district_code ?? null,
                    'ward_code' => $customerData->ward_code ?? null,
                    'address_detail' => $customerData->address_detail ?? null,
                    'note' => $customerData->note ?? null,
                    'created_at' => $customerData->created_at,
                    'updated_at' => $customerData->updated_at,
                ];
            });

            return responseApi(collect($data)->collapse(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            $this->model::find($this->request->id)->update($this->request->all());
            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete()
    {
        try {
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
