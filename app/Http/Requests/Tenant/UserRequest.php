<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UserRequest extends FormRequest
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
        $id = "," . $this->id;
        $rules = [
            "id" => [
                "required",
                "exists:App\Models\Tenant\User,id"
            ],
            "name" => [
                "required",
                "max:255",
            ],
            "email" => [
                "regex" => "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                "max" => "max:255",
                "required",
            ],
            "tel" => [
                "nullable",
                "min:10",
                "regex:/^(03|05|07|08|09)+([0-9]{8})$/",
            ],
            "status" => [
                "in:0,1",
                "required"
            ],
            "location_id" => [
                "exists:App\Models\Tenant\Location,id",
                "required"
            ],
            "password" => [
                "min:6",
                "max:255"
            ],
            "role_id" => [
                "required",
                "exists:App\Models\Tenant\Role,id"
            ],
        ];

        switch ($getUrl) {
            case "store":
            case "update":
                if ($getUrl == "update") {
                    array_push($rules['name'], "unique:App\Models\Tenant\User,name" . $id);
                    array_push($rules['email'], "unique:App\Models\Tenant\User,email" . $id);
                    array_push($rules['tel'], "unique:App\Models\Tenant\User,tel" . $id);
                    array_push($rules['password'], "nullable");
                } else {
                    array_push($rules['name'], "unique:App\Models\Tenant\User,name");
                    array_push($rules['email'], "unique:App\Models\Tenant\User,email");
                    array_push($rules['tel'], "unique:App\Models\Tenant\User,tel");
                    array_push($rules['password'], "required");
                }
                return [
                    "id" => $getUrl == "update" ? $rules["id"] : [],
                    "name" => $rules["name"],
                    "email" => $rules["email"],
                    "tel" => $rules["tel"],
                    "status" => $rules["status"],
                    "location_id" => $rules["location_id"],
                    "password" => $rules["password"],
                    "role_id" => $rules["role_id"],
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
            "id.required" => "Mã người dùng không được để trống!",
            "id.exists" => "Mã người dùng không tồn tại!",
            "name.required" => "Tên người dùng không được để trống!",
            "name.max" => "Tên người dùng đã vượt quá ký tự cho phép!",
            "name.unique" => "Tên người dùng đã tồn tại!",
            "email.regex" => "Email sai định dạng!",
            "email.max" => "Email đã vượt quá ký tự cho phép!",
            "email.unique" => "Email đã tồn tại!",
            "tel.min" => "Số điện thoại sai định dạng!",
            "tel.regex" => "Số điện thoại sai định dạng!",
            "tel.unique" => "Số điện thoại đã tồn tại!",
            "status.in" => "Trạng thái không hợp lệ!",
            "location_id.exists" => "Địa điểm không tồn tại!",
            "password.required" => "Mật khẩu không được để trống!",
            "password.min" => "Mật khẩu phải có ít nhất 6 ký tự!",
            "password.max" => "Mật khẩu đã vượt quá ký tự cho phép!",
            "role_id.required" => "Vai trò không được để trống!",
            "role_id.exists" => "Vai trò không tồn tại!",
        ];
    }
}
