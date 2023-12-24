<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Variation;

class VariationController extends Controller
{
    public function getListVariation()
    {
        try {
            $product = Variation::with(['productName'])->get();
            $result = $product->map(function($data){
                return [
                    'id' => $data->id,
                    'product_id' => $data->product_id,
                    'product_name_variation' => $data->productName[0]->name . ' - ' . $data->variation_name,
                    'sku' => $data->sku,
                    'barcode' => $data->barcode,
                    'variation_name' => $data->variation_name,
                    'display_name' => $data->display_name,
                    'image' => $data->image,
                    'price_import' => $data->price_import,
                    'price_export' => $data->price_export,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            });
            return responseApi($result,true);
        }catch (\Throwable $throwable){
            return responseApi($throwable->getMessage());
        }

    }
}
