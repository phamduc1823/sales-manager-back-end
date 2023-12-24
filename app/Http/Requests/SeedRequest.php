<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SeedRequest extends FormRequest
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
                'name' => 'required|unique:App\Models\Seed,name',
                'type' => 'required|in:0,1,2',
            ];
        }

        if($url == "update"){
            return [
                'name' => 'required|unique:App\Models\Seed,name,'.$this->id,
                'type' => 'required|in:0,1,2',
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            "name.required" => "Tên không được để trống!",
            "type.required" => "Loại dữ liệu không được để trống!",
            "name.unique" => "Tên đã tồn tại!",
        ];
    }
}
