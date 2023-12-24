<?php

namespace App\Http\Requests;

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
        $url = Str::afterLast($this->url(), '/');
        if ($url == 'store') {
            return [
                "email_user" => [
                    "regex" => "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                    "max" => "max:255",
                    "unique" => "unique:App\Models\User,email",
                    "required"
                ],
                "tel" => [
                    "nullable",
                    "min:10",
                    "unique" => "unique:App\Models\User,tel",
                    "regex:/^(03|05|07|08|09)+([0-9]{8})$/"
                ],
                'password' => 'required',
                'ten_user' => 'required'
            ];
        }
        if ($url == 'update') {
            return [
                'ten_user' => 'required',
                "email_user" => [
                    "regex" => "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                    "max" => "max:255",
                    "unique" => "unique:App\Models\User,email,".$this->id,
                    "required"
                ],
                "tel" => [
                    "nullable",
                    "min:10",
                    "unique" => "unique:App\Models\User,tel,".$this->id,
                    "regex:/^(03|05|07|08|09)+([0-9]{8})$/"
                ],

            ];
        }
        return [];
    }

    public function messages()
    {
        return [
            'email_user.required' => 'email_user không được để trống',
            'email_user.unique' => 'email_user đã tồn tại',
            'email_user.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'ten_user.required' => 'Tên người dùng không được để trống',
            'tel.regex' => 'Số điện thoại không hợp lệ',
            'tel.min' => 'Số điện thoại không hợp lệ',
            'tel.unique' => 'Số điện thoại đã tồn tại',
        ];
    }
}
