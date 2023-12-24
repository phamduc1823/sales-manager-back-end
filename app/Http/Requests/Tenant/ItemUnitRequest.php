<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ItemUnitRequest extends FormRequest
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
                "exists:App\Models\Tenant\ItemUnit,id"
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
                    array_push($rules['name'], "unique:App\Models\Tenant\ItemUnit,name" . $id);
                } else {
                    array_push($rules['name'], "unique:App\Models\Tenant\ItemUnit,name");
                }

                return [
                    "id" => $updateId,
                    "name" => $rules['name'],
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
            "id.required" => "Mã đơn vị tính không được để trống!",
            "id.exists" => "Mã đơn vị tính không tồn tại!",
            "name.required" => "Tên đơn vị tính không được để trống!",
            "name.unique" => "Tên đơn vị tính đã tồn tại!",
            "name.max" => "Tên đơn vị tính đã vượt quá ký tự cho phép!"
        ];
    }
}
