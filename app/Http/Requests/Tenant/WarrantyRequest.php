<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class WarrantyRequest extends FormRequest
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
                "exists:App\Models\Tenant\Warranty,id"
            ],
            "name" => [
                "required",
                "max:255"
            ],
            "unit" => [
                "required",
                "in:0,1,2"
            ],
            "period" => [
                "required",
                "gt:0",
                "max:500"
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                if ($getUrl == "update") {
                    array_push($rules['name'], "unique:App\Models\Tenant\Warranty,name" . $id);
                } else {
                    array_push($rules['name'], "unique:App\Models\Tenant\Warranty,name");
                }

                return [
                    "id" => $updateId,
                    "name" => $rules["name"],
                    "unit" => $rules["unit"],
                    "period" => $rules["period"]
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
            "id.required" => "Mã bảo hành không được để trống!",
            "id.exists" => "Mã bảo hành không tồn tại!",
            "name.required" => "Tên bảo hành không được để trống!",
            "name.max" => "Tên bảo hành đã vượt quá ký tự cho phép!",
            "unit.required" => "Đơn vị tính không được để trống!",
            "unit.in" => "Đơn vị tính không được hợp lệ!",
            "period.required" => "Thời hạn bảo hành không được để trống!",
            "period.gt" => "Thời hạn bảo hành phải lớn hơn 0!",
            "period.max" => "Thời hạn bảo hành đã vượt quá ký tự cho phép!"
        ];
    }
}
