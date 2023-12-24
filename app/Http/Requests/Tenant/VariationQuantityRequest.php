<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class VariationQuantityRequest extends FormRequest
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
        return [
            "variation_id" => [
                "required",
            ],
            "quantity" => [
                "required",
                "numeric",
                "integer"
            ]
        ];
    }
    public function messages()
    {
       return [
            "variation_id.required" => "Vui lòng chọn sản phẩm",
            "quantity.required" => "Vui lòng nhập số lượng",
            "quantity.numeric" => "Số lượng phải là số",
            "quantity.gt" => "Số lượng phải lớn hơn 0",
            "quantity.integer" => "Số lượng phải là số nguyên"
       ];
    }
}
