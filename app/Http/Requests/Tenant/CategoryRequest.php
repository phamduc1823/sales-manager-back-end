<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CategoryRequest extends FormRequest
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
        $rules =  [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Category,id"
            ],
            "name" => [
                "required",
                "max:255"
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                if ($getUrl == "update") {
                    array_push($rules['name'], "unique:App\Models\Tenant\Category,name" . $id);
                } else {
                    array_push($rules['name'], "unique:App\Models\Tenant\Category,name");
                }

                return [
                    "id" => $updateId,
                    "name" => $rules["name"],
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
             "id.required" => "Mã danh mục không được để trống!",
             "id.exists" => "Danh mục không tồn tại!",
             "name.required" => "Tên danh mục không được để trống!",
             "name.unique" => "Tên danh mục đã tồn tại!",
             "name.max" => "Tên danh mục đã vượt quá ký tự cho phép!"
         ];
     }
}
