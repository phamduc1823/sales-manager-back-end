<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ProductRequest extends FormRequest
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

        $rules =  [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Product,id"
            ],
            "name" => [
                "required",
                "max:255"
            ],
            "image" => [
                "nullable",
                "max:255"
            ],
            "weight" => [
                "nullable",
                "max:10000"
            ],
            "description" => [
                "nullable",
                "max: 500"
            ],
            "manage_type" => [
                "required",
                "in:0,1"
            ],
            "brand_id" => [
                "exists:App\Models\Tenant\Brand,id",
                "nullable" => "nullable"
            ],
            "warranty_id" => [
                "exists:App\Models\Tenant\Warranty,id",
                "nullable" => "nullable"
            ],
            "item_unit_id" => [
                "exists:App\Models\Tenant\ItemUnit,id",
                "nullable" => "nullable"
            ],
            "category_id" => [
                "exists:App\Models\Tenant\Category,id",
                "nullable" => "nullable"
            ],
            "status" => [
                "required",
                "in:0,1"
            ],
            "attributes" => [
                "nullable"
            ],
            "attributes.*.id" => [
                "exists:App\Models\Tenant\Attribute,id"
            ],
            "attributes.*.name" => [
                "required",
                "max:255"
            ],
            "attributes.*.attribute_values" => [
                "required"
            ],
            "attributes.*.attribute_values.*.id" => [
                "exists:App\Models\Tenant\AttributeValue,id"
            ],
            "attributes.*.attribute_values.*.value" => [
                "required",
                "max:255"
            ],
            "variations" => [
                "nullable"
            ],
            "variations.*.id" => [
                "exists:App\Models\Tenant\Variation,id"
            ],
            "variations.*.sku" => [
                "nullable",
                "max:255"
            ],
            "variations.*.barcode" => [
                "nullable",
                "max:255"
            ],
            "variations.*.variation_name" => [
                "required",
                "max:255"
            ],
            "variations.*.display_name" => [
                "required",
                "max:255"
            ],
            "variations.*.image" => [
                "nullable",
                "max:255"
            ],
            "variations.*.price_import" => [
                "required"
            ],
            "variations.*.price_export" => [
                "required"
            ],
            "variations.*.status" => [
                "required"
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $nameUrl = "update";
                $updateId = $getUrl == $nameUrl ? $rules["id"] : [];
                $updateIdAttribute = $getUrl == $nameUrl ? $rules["attributes.*.id"] : [];
                $updateIdAttributeValue = $getUrl == $nameUrl ? $rules['attributes.*.attribute_values.*.id'] : [];
                $updateIdVariation = $getUrl == $nameUrl ? $rules['variations.*.id'] : [];
                $products = $this->request->all();
                $brand_id = $products['brand_id'] == 0 ? $rules['brand_id']['nullable'] : $rules['brand_id'];
                $warranty_id = $products['warranty_id'] == 0 ? $rules['warranty_id']['nullable'] : $rules['warranty_id'];
                $item_unit_id = $products['item_unit_id'] == 0 ? $rules['item_unit_id']['nullable'] : $rules['item_unit_id'];
                $category_id = $products['category_id'] == 0 ? $rules['category_id']['nullable'] : $rules['category_id'];

                return [
                    "id" => $updateId,
                    "name" => $rules["name"],
                    "image" => $rules["image"],
                    "weight" => $rules["weight"],
                    "description" => $rules["description"],
                    "manage_type" => $rules["manage_type"],
                    "brand_id" => $brand_id,
                    "warranty_id" => $warranty_id,
                    "item_unit_id" => $item_unit_id,
                    "category_id" => $category_id,
                    "status" => $rules["status"],
                    "attributes" => $rules['attributes'],
                    "attributes.*.id" => $updateIdAttribute,
                    "attributes.*.name" => $rules['attributes.*.name'],
                    "attributes.*.attribute_values" => $rules['attributes.*.attribute_values'],
                    "attributes.*.attribute_values.*.id" => $updateIdAttributeValue,
                    "attributes.*.attribute_values.*.value" => $rules['attributes.*.attribute_values.*.value'],
                    "variations" => $rules['variations'],
                    "variations.*.id" => $updateIdVariation,
                    "variations.*.sku" => $rules['variations.*.sku'],
                    "variations.*.barcode" => $rules['variations.*.barcode'],
                    "variations.*.variation_name" => $rules['variations.*.variation_name'],
                    "variations.*.display_name" => $rules['variations.*.display_name'],
                    "variations.*.image" => $rules['variations.*.image'],
                    "variations.*.price_import" => $rules['variations.*.price_import'],
                    "variations.*.price_export" => $rules['variations.*.price_export'],
                    "variations.*.status" => $rules['variations.*.status']
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
            "id.required" => "Mã sản phẩm không được để trống!",
            "id.exists" => "Mã sản phẩm không tồn tại!",
            "name.required" => "Tên sản phẩm không được để trống!",
            "name.max" => "Tên sản phẩm đã vượt quá ký tự cho phép!",
            "image.max" => "Ảnh sản phẩm đã vượt quá ký tự cho phép!",
            "weight.max" => "Khối lượng đã vượt quá ký tự cho phép!",
            "description.max" => "Mô tả đã vượt quá ký tự cho phép!",
            "manage_type.in" => "Phương thức quản lý sản phẩm không hợp lệ!",
            "brand_id.exists" => "Mã thương hiệu không tồn tại!",
            "warranty_id.exists" => "Mã bảo hành không tồn tại!",
            "item_unit_id.exists" => "Mã đơn vị tính không tồn tại!",
            "category_id.exists" => "Mã danh mục không tồn tại!",
            "status.in" => "Trạng thái sản phẩm không hợp lệ!",
            "attributes.*.id.exists" => "Mã thuộc tính không tồn tại!",
            "attributes.*.name.required" => "Tên thuộc tính không được để trống!",
            "attributes.*.name.max" => "Tên thuộc tính đã vượt quá ký tự cho phép!",
            "attributes.*.attribute_values.required" => "Giá trị của thuộc tính không được để trống!",
            "attributes.*.attribute_values.*.id.exists" => "Mã giá trị của thuộc tính không tồn tại!",
            "attributes.*.attribute_values.*.value.required" => "Tên giá trị của thuộc tính không được để trống!",
            "attributes.*.attribute_values.*.value.max" => "Tên giá trị của thuộc tính đã vượt quá ký tự cho phép!",
            "variations.*.id.exists" => "Mã phiên bản không tồn tại!",
            "variations.*.sku.max" => "Sku đã vượt quá ký tự cho phép!",
            "variations.*.barcode.max" => "Barcode đã vượt quá ký tự cho phép!",
            "variations.*.variation_name.required" => "Tên phiên bản không được để trống!",
            "variations.*.variation_name.max" => "Tên phiên bản đã vượt quá ký tự cho phép!",
            "variations.*.display_name.required" => "Tên hiển thị không được để trống!",
            "variations.*.display_name.max" => "Tên hiển thị đã vượt quá ký tự cho phép!",
            "variations.*.image.max" => "Ảnh phiên bản đã vượt quá ký tự cho phép!",
            "variations.*.price_import.required" => "Giá tiền phiên bản nhập vào không được để trống!",
            "variations.*.price_export.required" => "Giá tiền phiên bản bán ra không được để trống!",
            "variations.*.status.required" => "Trạng thái phiên không được để trống!",
        ];
    }
}
