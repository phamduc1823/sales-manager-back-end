<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class GroupSupplierRequest extends FormRequest
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
                "exists:App\Models\Tenant\GroupCustomer,id"
            ],
            "name" => [
                "required"
            ],
            "description" => [
                "max:500",
                "nullable"
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                if ($getUrl == "update") {
                    array_push($rules['name'], "unique:App\Models\Tenant\GroupCustomer,name" . $id);
                } else {
                    array_push($rules['name'], "unique:App\Models\Tenant\GroupCustomer,name");
                }

                return [
                    "id" => $updateId,
                    "name" => $rules["name"],
                    "description" => $rules["description"]
                ];
            case "show":
            case "delete":
                return ["id" => $rules["id"]];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            "id.required" => "Mã nhóm nhà cung cấp không được để trống!",
            "id.exists" => "Mã nhóm nhà cung cấp không tồn tại!",
            "name.required" => "Tên nhóm nhà cung cấp không được để trống!",
            "name.unique" => "Tên nhóm nhà cung cấp đã tồn tại!",
            "description.max" => "Giới thiệu đã vượt quá ký tự cho phép!"
        ];
    }
}
