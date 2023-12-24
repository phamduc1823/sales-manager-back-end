<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ConfigRequest extends FormRequest
{
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
        $rules =  [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Config,id"
            ],
            "business_name" => [
                "required",
                "max:255",
            ],
            "email" => [
                "regex" => "regex:/^[\w-\.]+@([\w-]+\.)+[\w-]{2,}$/",
                "max" => "max:255",
                "nullable"
            ],
            "tel"=>[
                "regex" => "regex:/^(03|05|07|08|09)+([0-9]{8})$/",
                "min" => "min:10",
                "nullable"
            ],
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                if ($getUrl == "update") {
                    array_push($rules['email'], "unique:App\Models\Tenant\Config,email" . $id);
                } else {
                    array_push($rules['email'], "unique:App\Models\Tenant\Config,email");
                }

                return [
                    "id" => $updateId,
                    "business_name" => $rules["business_name"],
                    "email" => $rules["email"],
                    "tel" => $rules["tel"]
                ];
            case "delete":
                return ["id" => $rules["id"]];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            "id.required" => "Mã cấu hình khônh được để trống!",
            "id.exists" => "Mã cấu hình không tồn tại!",
            "business_name.required" => "Tên doanh nghiệp không được để trống!",
            "business_name.max" => "Tên doanh nghiệp đã vượt quá ký tự cho phép!",
            "email.regex" => "Email sai định dạng!",
            "email.max" => "Email đã vượt quá ký tự cho phép!",
            "email.unique" => "Email đã tồn tại!",
            "tel.regex" => "Số điện thoại sai định dạng!",
            "tel.min" => "Số điện thoại sai định dạng!"
        ];
    }
}
