<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Address\Commune;
use App\Models\Pricing;
use App\Models\Tenant;
use App\Models\Tenant\Inventory;
use App\Models\Tenant\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LocationController extends Controller
{
    public function validation(Request $request)
    {
        $rules = [
            'name' => 'required',
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|integer|min:0',
            'is_main' => 'required|integer|min:0',
            'tel' => ['nullable', 'min:10', 'regex:/^(03|05|07|08|09)+([0-9]{8})$/'],
            'email' => ['nullable', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        ];
        $message = [
            'name.required' => 'Tên phải được nhập',
            'image.required' => 'Ảnh phải được nhập',
            'image.image' => 'Ảnh không đúng định dạng',
            'image.mimes' => 'Ảnh không hợp lệ',
            'image.max' => 'Ảnh không đc quá :max mb',
            'status.required' => 'Trạng thái phải được chọn',
            'status.integer' => 'Trạng thái không hợp lệ',
            'status.min' => 'Trạng thái không hợp lệ',
            'is_main.required' => 'Cơ sở mặc định phải được chọn',
            'is_main.integer' => 'Cơ sở mặc định không hợp lệ',
            'is_main.min' => 'Cơ sở mặc định không hợp lệ',
            'tel.regex' => 'Số điện thoại không hợp lệ',
            'tel.min' => 'Số điện thoại không hợp lệ',
            'email.regex' => 'Email không hợp lệ',
        ];
        return $request->validate($rules, $message);
    }

    public function list(Request $request)
    {
        try {
            $locations = Location::with(['inventories'])
                ->orderBy('id', 'desc')
                ->get();
            $return = $locations->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'image' => $data->image,
                    'description' => $data->description,
                    'tel' => $data->tel,
                    'email' => $data->email,
                    'province_id' => $data->province_code,
                    'district_id' => $data->district_code,
                    'commune_id' => $data->ward_code,
                    'address_detail' => $data->address_detail,
                    'status' => $data->status,
                    'is_main' => $data->is_main,
                    'created_by' => $data->created_by,
                    'inventory' => $data->inventories->first(),
                ];
            });
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            $data = Location::query()->findOrFail($request->id);
            $return = [
                'name' => $data->name,
                'image' => $data->image,
                'description' => $data->description,
                'tel' => $data->tel,
                'email' => $data->email,
                'province_id' => $data->province_code,
                'district_id' => $data->district_code,
                'commune_id' => $data->ward_code,
                'address_detail' => $data->address_detail,
                'status' => $data->status,
                'is_main' => $data->is_main,
                'created_by' => $data->created_by
            ];
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validation($request);
            if (!$this->checkCountInventory()) {
                return responseApi('Số lượng kho và chi nhánh đã đạt tối đa', false);
            }
            $countMain = Location::where('is_main', 1)->count();
            if ($countMain > 0 && $request->is_main == 1) {
                return responseApi('Đã có cơ sở mặc định', false);
            }
            $file = $request->file('image');
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $fullpath = $file->storeAs('images', $fileName, 'public');
            }
            $data = [
                'name' => $request->name,
                'image' => $fullpath ?? null,
                'description' => $request->description ?? null,
                'tel' => $request->tel ?? null,
                'email' => $request->email ?? null,
                'province_code' => $request->province_code ?? null,
                'district_code' => $request->district_code ?? null,
                'ward_code' => $request->ward_code ?? null,
                'address_detail' => $request->address_detail,
                'status' => $request->status ?? 0,
                'is_main' => $request->is_main ?? 0,
                'created_by' => $request->created_by ?? null
            ];
            $this->createLocationAndInventory($data);
            DB::commit();
            return responseApi('Thêm thành công', true);
        } catch (ValidationException $exception) {
            DB::rollBack();
            return responseApi($exception->errors());
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validation($request);
            $location = Location::query()->find($request->id);
            if ($location->is_main == 0 && $request->is_main == 1) {
                $countMain = Location::where('is_main', 1)->count();
                if ($countMain > 0 && $request->is_main == 1) {
                    return responseApi('Đã có cơ sở mặc định', false);
                }
            }
            if ($request->hasFile('image')) {
                Storage::delete('public/' . $location->image);
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $fullpath = $file->storeAs('images', $fileName, 'public');
            } else {
                $fullpath = $location->image;
            }
            $data = [
                'name' => $request->name,
                'image' => $fullpath,
                'description' => $request->description,
                'tel' => $request->tel,
                'email' => $request->email,
                'province_code' => $request->province_code,
                'district_code' => $request->district_code,
                'ward_code' => $request->ward_code,
                'address_detail' => $request->address_detail,
                'status' => $request->status,
                'is_main' => $request->is_main,
                'created_by' => $request->created_by
            ];
            $location->update($data);
            Inventory::query()->where('location_id', $request->id)->update([
                'location_id' => $request->id,
                'name' => 'Kho ' . $request->name,
                'status' => $request->status,
                'code' => Str::slug('Kho ' . $request->name)
            ]);
            DB::commit();
            return responseApi('Cập nhật thành công', true);
        } catch (ValidationException $exception) {
            DB::rollBack();
            return responseApi($exception->errors());
        }
    }

    public function delete(Request $request)
    {
        try {
            $location = Location::query()->find($request->id);
            if ($location) {
                $variationQuantity = Tenant\VariationQuantity::query()->where('inventory_id', $location->id);
                if ($variationQuantity->sum('quantity') > 0) {
                    return responseApi('Cơ sở này đang được sử dụng và còn hàng trong kho', false);
                } else {
                    $variationQuantity->delete();
                    Inventory::query()->where('location_id', $request->id)->delete();
                    $location->delete();
//                    Storage::delete('public/' . $location->image);
                    return responseApi('Xoá thành công', true);
                }
            }
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function createLocationAndInventory($data)
    {
        $location = Location::query()->create($data);
        Inventory::query()->create([
            'location_id' => $location->id,
            'name' => 'Kho ' . $location->name,
            'status' => $location->status,
            'code' => Str::slug('Kho ' . $location->name)
        ]);
    }

    protected function checkCountInventory()
    {
        $pricingId = Tenant::current()->first()->pricing_id;
        $max_inventory = Pricing::where('id', $pricingId)->first()?->max_locations;
        $countLocation = Location::query()->count();
        $countInventory = Inventory::query()->count();
        if ($countInventory == $max_inventory && $countLocation == $max_inventory) {
            return false;
        }
        return true;
    }
}
