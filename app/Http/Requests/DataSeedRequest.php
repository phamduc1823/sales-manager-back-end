<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class DataSeedRequest extends FormRequest
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
            'business_field_id' => 'required',
            'seed_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            "business_field_id.required" => "Ngành hàng không được để trống!",
            "seed_id.required" => "Loại dữ liệu mẫu không được để trống!",
        ];
    }
}
