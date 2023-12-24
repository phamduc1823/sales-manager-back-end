<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SupplierRequest extends FormRequest
{
    use TFailedValidation;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $getUrl = Str::afterLast($this->url(), '/');
        $id = ",".$this->id;
        $rules = [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Customer,id"
            ],
            "group_customer_id" => [
                "exists:App\Models\Tenant\GroupCustomer,id",
                "nullable"
            ],
            "type" => [
                "in:0,1",
                "nullable"
            ],
            "name" => [
                "required",
                "max:255"
            ],
            "email" => [
                "regex" => "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                "max" => "max:255",
                "nullable"
            ],
            "tel" => [
                "required",
                "max:255",
                "regex:/^(03|05|07|08|09)[0-9]{7,10}$/"
            ],
            "status" => [
                "in:0,1",
                "nullable"
            ],
            "province_code" => [
                "numeric",
                "nullable"
            ],
            "district_code" => [
                "numeric",
                "nullable"
            ],
            "ward_code" => [
                "numeric",
                "nullable"
            ],
            "address_detail" => [
                "max:500",
                "nullable"
            ],
            "note" => [
                "max:500",
                "nullable"
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                if ($getUrl == "update") {
                    array_push($rules['name'], "unique:App\Models\Tenant\Customer,name" . $id);
                } else {
                    array_push($rules['name'], "unique:App\Models\Tenant\Customer,name");
                }

                if ($getUrl == "update") {
                    array_push($rules['email'], "unique:App\Models\Tenant\Customer,email" . $id);
                } else {
                    array_push($rules['email'], "unique:App\Models\Tenant\Customer,email");
                }


                if ($getUrl == "update") {
                    array_push($rules['tel'], "unique:App\Models\Tenant\Customer,tel" . $id);
                } else {
                    array_push($rules['tel'], "unique:App\Models\Tenant\Customer,tel");
                }

                return [
                    "id" => $updateId,
                    "group_customer_id" => $rules['group_customer_id'],
                    "type" => $rules['type'],
                    "name" => $rules['name'],
                    "email" => $rules['email'],
                    "tel" => $rules['tel'],
                    "status" => $rules['status'],
                    "province_code" => $rules['province_code'],
                    "district_code" => $rules['district_code'],
                    "ward_code" => $rules['ward_code'],
                    "address_detail" => $rules['address_detail'],
                    "note" => $rules['note']
                ];
            case "show":
            case "delete":
                return ["id" => $rules['id'],];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            "id.required" => "Mã nhà cung cấp không được để trống!",
            "id.exists" => "Mã nhà cung cấp không tồn tại!",
            "group_customer_id.exists" => "Nhóm nhà cung cấp không tồn tại!",
            "type.in" => "Giá trị loại nhà cung cấp không hợp lệ!",
            "name.required" => "Tên nhà cung cấp không được để trống!",
            "name.max" => "Tên nhà cung cấp đã vượt quá ký tự cho phép!",
            "name.unique" => "Tên nhà cung cấp đã tồn tại!",
            "email.regex" => "Email sai định dạng!",
            "email.max" => "Email đã vượt quá ký tự cho phép!",
            "email.unique" => "Email đã tồn tại!",
            "tel.required" => "Số điện thoại không được để trống!",
            "tel.min" => "Số điện thoại sai định dạng!",
            "tel.regex" => "Số điện thoại sai định dạng!",
            "tel.unique" => "Số điện thoại đã tồn tại!",
            "status.in" => "Trạng thái không hợp lệ!",
            "province_code.numeric" => "Tỉnh chỉ được nhập số!",
            "district_code.numeric" => "Quận/huyện chỉ được nhập số!",
            "ward_code.numeric" => "Xã/phường Chỉ được nhập số!",
            "address_detail.max" => "Địa chỉ cụ thể đã vượt quá ký tự cho phép!",
            "note.max" => "Ghi chú đã vượt quá ký tự cho phép!",
        ];
    }
}
