<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class OrderRequest extends FormRequest
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
        $id = ",".$this->id;
        $rules =  [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Order,id"
            ],
            "location_id" => [
                "required",
                "exists:App\Models\Tenant\Location,id"
            ],
            "customer_id" => [
                "required",
                "exists:App\Models\Tenant\Customer,id"
            ],
            "created_by" => [
                "required",
                "exists:App\Models\Tenant\User,id"
            ],
            "discount" => [
                "nullable",
                "gte:0"
            ],
            "discount_type" => "nullable",
            "tax" => [
                "nullable",
                "gte:0"
            ],
            "service_charge" => [
                "nullable",
                "gte:0"
            ],
            "total_product" => [
                "nullable",
                "gt:0"
            ],
            "total_price" => [
                "nullable",
                "gt:0"
            ],
            "status" => "required",
            "payment_status" => "required",
            "order_details" => "required",
            "order_details.*.variation_quantities.*.variation_id" => [
                "required",
                "exists:App\Models\Tenant\Variation,id"
            ],
            "order_details.*.batch_id" => [
                "nullable",
                "exists:App\Models\Tenant\Batch,id"
            ],
            "order_details.*.discount" => [
                "nullable",
                "gte:0"
            ],
            "order_details.*.discount_type" => "nullable",
            "order_details.*.tax" => [
                "nullable",
                "gte:0"
            ],
            "order_details.*.quanity" => [
                "required",
                "gt:0"
            ],
            "order_details.*.result" => [
                "required",
                "gte:0"
            ],
        ];

        switch ($getUrl){
            case "store":
                return [
                    "location_id" => $rules['location_id'],
                    "customer_id" => $rules['customer_id'],
                    "created_by" => $rules['created_by'],
                    "discount" => $rules['discount'],
                    "discount_type" => $rules['discount_type'],
                    "tax" => $rules['tax'],
                    "service_charge" => $rules['service_charge'],
                    "total_product" => $rules['total_product'],
                    "total_price" => $rules['total_price'],
                    "status" => $rules['status'],
                    "order_details" => $rules['order_details'],
                    "payment_status" => $rules['payment_status'],
                    "order_details.*.variation_quantities.*.variation_id" =>
                        $rules['order_details.*.variation_quantities.*.variation_id'],
                    "order_details.*.batch_id" => $rules['order_details.*.batch_id'],
                    "order_details.*.discount" => $rules['order_details.*.discount'],
                    "order_details.*.discount_type" => $rules['order_details.*.discount_type'],
                    "order_details.*.tax" => $rules['order_details.*.tax'],
                    "order_details.*.quanity" => $rules['order_details.*.quanity'],
                    "order_details.*.result" => $rules['order_details.*.result']
                ];
            case "show":
                return ["id" => $rules["id"]];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            "id.required" => "Mã hóa đơn không được để trống!",
            "id.exists" => "Mã hóa đơn không tồn tại!",
            "location_id.required" => "Mã cơ sở được để trống!",
            "location_id.exists" => "Mã cơ sở không tồn tại!",
            "customer_id.required" => "Mã khách hàng được để trống!",
            "customer_id.exists" => "Mã khách hàng không tồn tại!",
            "created_by.required" => "Mã nhân viên được để trống!",
            "created_by.exists" => "Mã nhân viên không tồn tại!",
            "discount.gte" => "Chiết khấu phải lớn hơn hoặc bằng 0!",
            "tax.gte" => "Thuế phải lớn hơn hoặc bằng 0!",
            "service_charge.gte" => "Phí dịch vụ phải lớn hơn hoặc bằng 0!",
            "total_product.gt" => "Tổng tiền sản phẩm phải lớn hơn 0!",
            "total_price.gt" => "Tổng tiền phải lớn hơn 0!",
            "status.required" => "Trạng thái đơn không được để trống!",
            "payment_status.required" => "Trạng thái thanh toán không được để trống!",
            "order_details.required" => "Chi tiết hóa đơn không được để trống!",
            "order_details.*.variation_quantities.*.variation_id.required" => "Mã phiên bản không được để trống!",
            "order_details.*.variation_quantities.*.variation_id.exists" => "Mã phiên bản không tồn tại!",
            "order_details.*.batch_id.exists" => "Mã lô không tồn tại!",
            "order_details.*.discount.gte" => "Chiết khấu của chi tiết hóa đơn phải lớn hơn hoặc bằng 0!",
            "order_details.*.tax.gte" => "Thuế của chi tiết hóa đơn phải lớn hơn hoặc bằng 0!",
            "order_details.*.quanity.gt" => "Số lượng của chi tiết hóa đơn phải lớn hơn 0!",
            "order_details.*.result.gt" => "Tổng tiền của chi tiết hóa đơn phải lớn hơn 0!"
        ];
    }
}
