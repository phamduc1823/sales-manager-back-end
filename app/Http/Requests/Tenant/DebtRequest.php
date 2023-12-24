<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class DebtRequest extends FormRequest
{
    use TFailedValidation;
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
        $getUrl = Str::afterLast($this->url(), '/');
        $rules = [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Debt,id"
            ],
            "partner_id" => [
                "required"
            ],
            "partner_type" => [
                "nullable",
                "in:0,1"
            ],
            "type" => [
                "required",
                "in:0,1"
            ],
            "name" => [
                "required"
            ],
            "amount_debt" => [
                "required",
                "numeric",
                "gt:0"
            ],
            "note" => [
                "max:500",
                "nullable"
            ],
            "status" => [
                "in:0,1,2,3",
                "nullable"
            ],
            "location_id" => [
                "required",
                "exists:App\Models\Tenant\Location,id"
            ],
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                return [
                    "id" => $updateId,
                    "partner_id" => $rules['partner_id'],
                    "partner_type" => $rules['partner_type'],
                    "type" => $rules['type'],
                    "name" => $rules['name'],
                    "amount_debt" => $rules['amount_debt'],
                    "note" => $rules['note'],
                    "status" => $rules['status'],
                    "location_id" => $rules['location_id'],
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
            "id.required" => "Mã khoản nợ Không được để trống!",
            "id.exists" => "Mã khoản nợ không tồn tại!",
            "partner_id.required" => "Mã đối tác Không được để trống!",
            "partner_type.in" => "Mã loại khách hàng không hợp lệ!",
            "type.required" => "Loại công nợ Không được để trống!",
            "type.in" => "Loại công nợ không hợp lệ!",
            "name.required" => "Tên khoản nợ Không được để trống!",
            "amount_debt.required" => "Số tiền nợ Không được để trống!",
            "amount_debt.numeric" => "Số tiền nợ chỉ được nhập số!",
            "amount_debt.gt" => "Số tiền nợ phải lớn hơn 0!",
            "note.max" => "Ghi chú vượt quá ký tự cho phép!",
            "status.in" => "Trạng thái không hợp lệ!",
            "location_id.required" => "Mã cơ sở không được để trống!",
            "location_id.exists" => "Mã cơ sở không tồn tại!",
        ];
    }
}
