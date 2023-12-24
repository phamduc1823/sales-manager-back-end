<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'tenant_id' => 'required|exists:tenants,id',
            'pricing_id' => 'required|exists:pricings,id',
            'type' => 'required|in:0,1',
            'name' => 'required|min:3',
            'tel' => ['required', 'min:10', 'regex:/^(03|05|07|08|09)+([0-9]{8})$/'],
        ];
    }
    public function messages()
    {
        return [
            'tenant_id.required' => 'Tên cửa hàng không được để trống!',
            'tenant_id.exists' => 'Cửa hàng không tồn tại!',
            'pricing_id.required' => 'Gói không được để trống!',
            'pricing_id.exists' => 'Gói không tồn tại!',
            'type.required' => 'Loại yêu cầu không được để trống!',
            'type.in' => 'Loại yêu cầu không tồn tại!',
            'name.required' => 'Tên không được để trống!',
            'name.min' => 'Tên cần ít nhất 3 ký tự!',
            'tel.required' => 'Số điện thoại không được để trống!',
            'tel.regex' => 'Số điện thoại không hợp lệ!',
            'tel.min' => 'Số điện thoại không hợp lệ!',
        ];
    }
}
