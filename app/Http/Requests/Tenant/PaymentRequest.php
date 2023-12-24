<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'amount_in' => 'required|numeric',
            'amount_refund' => 'required|numeric',
            'payment_method' => 'required|in:0,1,2',
//            'created_by' => 'required',
        ];
    }
    public function messages()
    {
        return [
            "amount.required" => "Số tiền thanh toán không được để trống!",
            "amount.numeric" => "Số tiền thanh toán chỉ được nhập số!",
            "amount_in.required" => "Số tiền khách đưa không được để trống!",
            "amount_in.numeric" => "Số tiền khách đưa chỉ được nhập số!",
            "amount_refund.required" => "Số tiền trả lại không được để trống!",
            "amount_refund.numeric" => "Số tiền trả lại chỉ được nhập số!",
            "payment_method.required" => "Phương thức thanh toán không được để trống!",
            "payment_method.in" => "Phương thức thanh toán không hợp lệ!"
        ];
    }
}
