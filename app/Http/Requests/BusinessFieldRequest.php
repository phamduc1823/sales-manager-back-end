<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BusinessFieldRequest extends FormRequest
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
        $url = Str::afterLast($this->url(), '/');

        if($url == "store"){
            return [
                'name' => 'required',
                'code' => 'required|unique:App\Models\BusinessField,code',
                'detail' => 'required',
            ];
        }

        if($url == "update"){
            return [
                'name' => 'required',
                'code' => 'required|unique:App\Models\BusinessField,code,'.$this->id,
                'detail' => 'required',
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            "name.required" => "Tên không được để trống!",
            "code.required" => "Mã ngành hàng không được để trống",
            "code.unique" => "Mã ngành hàng đã tồn tại",
            "detail.required" => "Mô tả không được để trống",
        ];
    }
}
