<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PrintedFormRequest extends FormRequest
{
    use TFailedValidation;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        $getUrl = Str::afterLast($this->url(), '/');
        $id = ",".$this->id;
        $rules =  [
            "id" => [
                "required",
                "exists:App\Models\Tenant\PrintedForm,id"
            ],
            "name" => [
                "required",
                "max:255"
            ],
            "form" => [
                "required",
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                if ($getUrl == "update") {
                    array_push($rules['name'], "unique:App\Models\Tenant\PrintedForm,name" . $id);
                } else {
                    array_push($rules['name'], "unique:App\Models\Tenant\PrintedForm,name");
                }

                return [
                    "id" => $updateId,
                    "name" => $rules["name"],
                    "form" => $rules["form"]
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
            "id.required" => "Mã mẫu in không được để trống!",
            "id.exists" => "Mã mẫu in không tồn tại!",
            "name.required" => "Tên mẫu in không được để trống!",
            "name.unique" => "Tên mẫu in đã tồn tại!",
            "name.max" => "Tên mẫu in đã vượt quá ký tự cho phép!",
            "form.required" => "Mẫu in không được để trống!"
        ];
    }
}
