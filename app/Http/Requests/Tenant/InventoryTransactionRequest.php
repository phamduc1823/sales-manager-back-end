<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class InventoryTransactionRequest extends FormRequest
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
            "inventory_id" => [
                "required",
            ],
            "inventory_id_out" => [
                "required",
            ],
            "partner_id" => [
                "required",
            ],
            "partner_type" => [
                "required",
            ],
            "reason" => [
                "required",
            ],
            "created_by" => [
                "required",
            ],
            "inventory_transaction_details" => [
                "required",
            ],
            "inventory_transaction_details.*.variation_id" => [
                "required",
            ],
            "inventory_transaction_details.*.price" => [
                "required",
                "gt:0",
                "numeric"
            ],
            "inventory_transaction_details.*.price_type" => [
                "required",
                "in:0,1"
            ],
            "inventory_transaction_details.*.quantity" => [
                "required",
                "gt:0",
                "numeric",
                "integer"
            ],
        ];
        switch ($getUrl) {
            case "import/create":
            case "trans/store":
            $nameUrl = "trans/store";
            $createTransfer = $getUrl == $nameUrl ? $rules["inventory_id_out"] : [];
            $parter_id = $getUrl == $nameUrl ? $rules["partner_id"] : [];
            $parter_type = $getUrl == $nameUrl ? $rules["partner_type"] : [];
                return [
                    "inventory_id"=>$rules["inventory_id"],
                    "inventory_id_out"=>$createTransfer,
                    "partner_id"=>$parter_id,
                    "partner_type"=>$parter_type,
                    "reason"=>$rules["reason"],
                    "created_by"=>$rules["created_by"],
                    "inventory_transaction_details"=>$rules["inventory_transaction_details"],
                    "inventory_transaction_details.*.variation_id"=>$rules["inventory_transaction_details.*.variation_id"],
                    "inventory_transaction_details.*.price"=>$rules["inventory_transaction_details.*.price"],
                    "inventory_transaction_details.*.price_type"=>$rules["inventory_transaction_details.*.price_type"],
                    "inventory_transaction_details.*.quantity"=>$rules["inventory_transaction_details.*.quantity"],
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            "inventory_id.required" => "Mã kho Không được để trống!",
            "inventory_id_out.required" => "Mã kho xuất Không được để trống!",
            "partner_id.required" => "Mã đối tác Không được để trống!",
            "partner_type.required" => "Loại đối tác Không được để trống!",
            "reason.required" => "Lý do Không được để trống!",
            "created_by.required" => "Mã nhân viên Không được để trống!",
            "inventory_transaction_details.required" => "Chi tiết giao dịch hàng tồn kho Không được để trống!",
            "inventory_transaction_details.*.variation_id.required" => "Mã phiên bản Không được để trống!",
            "inventory_transaction_details.*.price.required" => "Giá tiền Không được để trống!",
            "inventory_transaction_details.*.price.gt" => "Giá tiền phải lớn hơn 0!",
            "inventory_transaction_details.*.price.numeric" => "Giá tiền chỉ được nhập số!",
            "inventory_transaction_details.*.price_type.required" => "Loại giá không được để trống!",
            "inventory_transaction_details.*.price_type.in" => "Loại giá không hợp lệ!",
            "inventory_transaction_details.*.quantity.required" => "Số lượng Không được để trống!",
            "inventory_transaction_details.*.quantity.gt" => "Giá tiền phải lớn hơn 0!",
            "inventory_transaction_details.*.quantity.numeric" => "Giá tiền chỉ được nhập số!",
            "inventory_transaction_details.*.quantity.integer" => "Giá tiền phải là số nguyên!",
        ];
    }
}
