<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PricingRequest extends FormRequest
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
                'name' => 'required|min:3|unique:App\Models\Pricing,name',
                'max_locations' => 'required|numeric|integer|gt:0',
                'max_users' => 'required|numeric|integer|gt:0',
                'price' => 'required|numeric|gte:0',
                'expiry_day' => 'required|numeric|integer|gte:0',
            ];
        }

        if($url == "update"){
            return [
                'name' => 'required|min:3|unique:App\Models\Pricing,name,'.$this->id,
                'max_locations' => 'required|numeric|integer|gt:0',
                'max_users' => 'required|numeric|integer|gt:0',
                'price' => 'required|numeric|gte:0',
                'expiry_day' => 'required|numeric|integer|gt:0',
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            "name.required" => "Không được để trống!",
            "name.unique" => "Tên đã tồn tại!",
            "max_locations.required" => "Số chi nhánh tối đa không được để trống",
            "max_users.required" => "Số người dùng tối đa không được để trống",
            "price.required" => "Giá/tháng không được để trống",
            "max_locations.numeric" => "Số chi nhánh tối đa phải là số",
            "max_users.numeric" => "Số người dùng tối đa phải là số",
            "price.numeric" => "Giá/tháng phải là số",
            "max_locations.gt" => "Số chi nhánh tối đa phải lớn hơn 0",
            "max_users.gt" => "Số người dùng tối đa phải lớn hơn 0",
            "max_locations.integer" => "Số chi nhánh tối đa phải là số nguyên",
            "max_users.integer" => "Số người dùng tối đa phải là số nguyên",
            "price.gte" => "Giá phải lớn hơn hoặc bằng 0",
            "expiry_day.required" => "Thời hạn không được để trống",
            "expiry_day.numeric" => "Thời hạn phải là số",
            "expiry_day.integer" => "Thời hạn phải là số nguyên",
            "expiry_day.gt" => "Thời hạn phải lớn hơn 0",
        ];
    }
}
