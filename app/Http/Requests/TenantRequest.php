<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class TenantRequest extends FormRequest
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
        if ($url == 'store') {
            return [
                'name_tenant' => 'required|max:255',
                'business_field' => $this->business_code ? 'nullable' : 'required',
                'user_id' => 'required',
                'due_at' => 'nullable',
                'pricing_id' => 'nullable',
                'business_name' => 'required|max:255',
                'address' => 'required|max:500'
            ];
        }
        return [];
    }

    public function messages()
    {
        return [
            'name_tenant.required' => 'Tên phải được nhập!',
            'name_tenant.max' => 'Tên nhập quá ký tự cho phép!',
            'business_field.required' => 'Lĩnh vực kinh doanh phải được chọn!',
            'business_name.required' => 'Vui lòng điền tên cửa hàng!',
            'business_name.max' => 'Tên cửa hàng nhập quá ký tự cho phép!',
            'user_id.required' => 'Người dùng phải được chọn!',
            'username.required' => 'Tên người dùng phải được nhập!',
            'email.required' => 'Email phải được nhập!',
            'email.email' => 'Email không đúng định dạng!',
            'password.required' => 'Mật khẩu phải được nhập!',
            'due_at.required' => 'Ngày hết hạn phải được nhập!',
            'due_at.in' => 'Ngày hết hạn ko hợp lệ!',
            'address.required' => "Địa chỉ phải được nhập!",
            'address.max' => "Địa chỉ đã nhập quá ký tự cho phép!",
        ];
    }
}
