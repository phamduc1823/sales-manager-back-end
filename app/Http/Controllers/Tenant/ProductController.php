<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ProductRequest;
use App\Models\Tenant\Attribute;
use App\Models\Tenant\AttributeValue;
use App\Models\Tenant\Product;
use App\Models\Tenant\Variation;
use App\Models\Tenant\VariationAttribute;
use App\Models\Tenant\VariationQuantity;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function __construct(
        private Product            $productModel,
        private Attribute          $attributeModel,
        private AttributeValue     $attributeValueModel,
        private Variation          $variationModel,
        private VariationAttribute $variationAttributeModel,
        private VariationQuantity  $variationQuantityModel,
        private ProductRequest     $request
    )
    {
    }

    /**
     * @path /tenant/api/v1/products
     * @method POST
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function list()
    {
        try {
            $productData = $this->productModel::with([
                'brand',
                'warranty',
                'itemUnit',
                'category',
                'attributes.attributeValues',
                'variations'
            ])
                ->orderBy('id', 'desc')->get();

            $data = $productData->map(function ($productData) {
                return [
                    'id' => $productData->id,
                    'name' => $productData->name,
                    'image' => $productData->image,
                    'weight' => $productData->weight,
                    'description' => $productData->description,
                    'manage_type' => $productData->manage_type,
                    'brand_id' => $productData->brand_id,
                    'brand_name' => $productData->brand ? $productData->brand->name : null,
                    'warranty_id' => $productData->warranty_id,
                    'warranty_name' => $productData->warranty ? $productData->warranty->name : null,
                    'item_unit_id' => $productData->item_unit_id,
                    'item_unit_name' => $productData->itemUnit ? $productData->itemUnit->name : null,
                    'category_id' => $productData->category_id,
                    'category_name' => $productData->category ? $productData->category->name : null,
                    'status' => $productData->status,
                    'attributes' => $productData->attributes ?
                        collect($productData->attributes)->map(function ($attributes) {
                            return [
                                'id' => $attributes->id,
                                'product_id' => $attributes->product_id,
                                'name' => $attributes->name,
                                'attribute_values' => $attributes->attributeValues ?
                                    collect($attributes->attributeValues)->map(function ($attributeValues) {
                                        return [
                                            'id' => $attributeValues->id,
                                            'attribute_id' => $attributeValues->attribute_id,
                                            'value' => $attributeValues->value
                                        ];
                                    }) : [],
                            ];
                        }) : [],
                    'variations' => $productData->variations ?
                        collect($productData->variations)->map(function ($variations) {
                            return [
                                'id' => $variations->id,
                                'product_id' => $variations->product_id,
                                'sku' => $variations->sku,
                                'barcode' => $variations->barcode,
                                'variation_name' => $variations->variation_name,
                                'display_name' => $variations->display_name,
                                'image' => $variations->image,
                                'price_import' => $variations->price_import,
                                'price_export' => $variations->price_export,
                                'status' => $variations->status
                            ];
                        }) : [],
                    "created_at" => Carbon::make($productData->created_at)->format('d/m/Y H:i'),
                    "updated_at" => Carbon::make($productData->updated_at)->format('d/m/Y H:i')
                ];
            });

            return responseApi($data, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function getListProduct()
    {
        try {
            if ($this->request->inventory_id) {
                $products = Variation::with([
                    'product.itemUnit',
                    'batchs',
                    'variationQuantities' => function ($query) {
                        $query->where('inventory_id', $this->request->inventory_id);
                    },
                ])
                    ->get()->groupBy('product_id');

            } else {
                $products = Variation::with([
                    'product.itemUnit',
                    'variationQuantities',
                    'batchs'
                ])->orderBy('id', 'desc')->get()->groupBy('product_id');
            }
            return responseApi($products, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), true);
        }
    }

    public function getListAttribute()
    {
        try {
            if ($this->request->location_id) {
                $query = Variation::with([
                    'attributeValues',
                    'variationQuantities',
                    'attributeValues.attribute'
                ])->whereHas('variationQuantities.inventory', function ($query) {
                    $query->where('location_id', $this->request->location_id);
                })->get();
            } else {
                $query = Variation::with([
                    'attributeValues',
                    'variationQuantities',
                    'attributeValues.attribute'
                ])->get();
            }
            $return = $query->map(function ($data) {
                return [
                    'attribute' => $data->attributeValues->map(function ($attributeValue) {
                        return [
                            $attributeValue->attribute->name => $attributeValue->value
                        ];
                    }),
                    'quantity' => $data->variationQuantities,
                    'price_import' => $data->price_import,
                    'price_export' => $data->price_export
                ];
            });
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), true);
        }
    }

    /**
     * @path /tenant/api/v1/products/store
     * @method POST
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store()
    {
        try {
            $arrayAttributeValues = [];
            $arrayVariations = [];

            $product = $this->productModel::create([
                'name' => $this->request->name,
                'image' => $this->request->image,
                'weight' => $this->request->weight,
                'description' => $this->request->description,
                'manage_type' => $this->request->manage_type,
                'brand_id' => $this->request->brand_id == 0 ? null : $this->request->brand_id,
                'warranty_id' => $this->request->warranty_id == 0 ? null : $this->request->warranty_id,
                'item_unit_id' => $this->request->item_unit_id == 0 ? null : $this->request->item_unit_id,
                'category_id' => $this->request->category_id == 0 ? null : $this->request->category_id,
                'status' => $this->request->status
            ]);

            if ($this->request['attributes']) {
                foreach ($this->request['attributes'] as $dataAttribute) {
                    $attribute = $this->attributeModel::create([
                        'product_id' => $product->id,
                        'name' => $dataAttribute['name']
                    ]);

                    foreach ($dataAttribute['attribute_values'] as $dataAttributeValue) {
                        $attributeValue = $this->attributeValueModel::create([
                            'attribute_id' => $attribute->id,
                            'value' => $dataAttributeValue['value']
                        ]);
                        array_push($arrayAttributeValues, $attributeValue->id);
                    }
                }
            }

            if ($this->request['variations']) {
                foreach ($this->request['variations'] as $dataVariation) {
                    $variation = $this->variationModel::create([
                        "product_id" => $product->id,
                        "sku" => $dataVariation['sku'],
                        "barcode" => $dataVariation['barcode'],
                        "variation_name" => $dataVariation['variation_name'],
                        "display_name" => $dataVariation['display_name'],
                        "image" => $dataVariation['image'],
                        "price_import" => $dataVariation['price_import'],
                        "price_export" => $dataVariation['price_export'],
                        "status" => $dataVariation['status']
                    ]);

                    array_push($arrayVariations, $variation->id);
                }

                if ($arrayAttributeValues) {
                    foreach ($arrayVariations as $keyVariation => $valueVariation) {
                        foreach ($arrayAttributeValues as $keyAttributeValue => $valueAttributeValue) {
                            $this->variationAttributeModel::create([
                                'variation_id' => $valueVariation,
                                'attribute_value_id' => $valueAttributeValue
                            ]);
                        }
                    }
                }
            }

            return responseApi("Tạo thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/products/show
     * @method POST
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function show()
    {
        try {
            $productData = $this->productModel::with([
                'brand',
                'warranty',
                'itemUnit',
                'category',
                'attributes.attributeValues',
                'variations'
            ])->where('id', $this->request->id)->get();

            $data = $productData->map(function ($productData) {
                return [
                    'id' => $productData->id,
                    'name' => $productData->name,
                    'image' => $productData->image,
                    'weight' => $productData->weight,
                    'description' => $productData->description,
                    'manage_type' => $productData->manage_type,
                    'brand_id' => $productData->brand_id,
                    'brand_name' => $productData->brand ? $productData->brand->name : null,
                    'warranty_id' => $productData->warranty_id,
                    'warranty_name' => $productData->warranty ? $productData->warranty->name : null,
                    'item_unit_id' => $productData->item_unit_id,
                    'item_unit_name' => $productData->itemUnit ? $productData->itemUnit->name : null,
                    'category_id' => $productData->category_id,
                    'category_name' => $productData->category ? $productData->category->name : null,
                    'status' => $productData->status,
                    'attributes' => $productData->attributes ?
                        collect($productData->attributes)->map(function ($attributes) {
                            return [
                                'id' => $attributes->id,
                                'product_id' => $attributes->product_id,
                                'name' => $attributes->name,
                                'attribute_values' => $attributes->attributeValues ?
                                    collect($attributes->attributeValues)->map(function ($attributeValues) {
                                        return [
                                            'id' => $attributeValues->id,
                                            'attribute_id' => $attributeValues->attribute_id,
                                            'value' => $attributeValues->value
                                        ];
                                    }) : [],
                            ];
                        }) : [],
                    'variations' => $productData->variations ?
                        collect($productData->variations)->map(function ($variations) {
                            return [
                                'id' => $variations->id,
                                'product_id' => $variations->product_id,
                                'sku' => $variations->sku,
                                'barcode' => $variations->barcode,
                                'variation_name' => $variations->variation_name,
                                'display_name' => $variations->display_name,
                                'image' => $variations->image,
                                'price_import' => $variations->price_import,
                                'price_export' => $variations->price_export,
                                'status' => $variations->status
                            ];
                        }) : [],
                    "created_at" => Carbon::make($productData->created_at)->format('d/m/Y H:i'),
                    "updated_at" => Carbon::make($productData->updated_at)->format('d/m/Y H:i')
                ];
            });

            return responseApi(collect($data)->collapse(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/products/update
     * @method POST
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update()
    {
        try {
            $arrayAttributeValues = [];
            $arrayVariations = [];

            $this->productModel::find($this->request->id)->update([
                'name' => $this->request->name,
                'image' => $this->request->image,
                'weight' => $this->request->weight,
                'description' => $this->request->description,
                'manage_type' => $this->request->manage_type,
                'brand_id' => $this->request->brand_id == 0 ? null : $this->request->brand_id,
                'warranty_id' => $this->request->warranty_id == 0 ? null : $this->request->warranty_id,
                'item_unit_id' => $this->request->item_unit_id == 0 ? null : $this->request->item_unit_id,
                'category_id' => $this->request->category_id == 0 ? null : $this->request->category_id,
                'status' => $this->request->status,
            ]);

            if ($this->request->status_attributes == 1) {
                if ($this->request['attributes']) {
                    $arrayIdVariationAttribute = [];

                    $variationAttribute = $this->productModel::query()
                        ->select('variation_attributes.variation_id')
                        ->join('variations', 'products.id', '=', 'variations.product_id')
                        ->join('variation_attributes', 'variations.id', '=', 'variation_attributes.variation_id')
                        ->where('products.id', $this->request->id)
                        ->get();

                    foreach ($variationAttribute as $v) {
                        array_push($arrayIdVariationAttribute, $v->variation_id);
                    }

                    $this->variationAttributeModel::whereIn('variation_id', $arrayIdVariationAttribute)->delete();

                    $product = $this->productModel::find($this->request->id);

                    $product->variations()->each(function ($variation) {
                        $variation->attributeValues()->each(function ($variationAttribute) {
                            $this->variationAttributeModel::where('id', $variationAttribute->id)->delete();
                        });
                        $variation->delete();
                    });

                    foreach ($this->request['attributes'] as $dataAttribute) {
                        $this->attributeModel::find($dataAttribute['id'])->update(['name' => $dataAttribute['name']]);

                        foreach ($dataAttribute['attribute_values'] as $dataAttributeValue) {
                            isset($dataAttributeValue['id']) ?
                                $this->attributeValueModel::find($dataAttributeValue['id'])->update([
                                    'value' => $dataAttributeValue['value']
                                ]) :
                                $attributeValue = $this->attributeValueModel::create([
                                    'attribute_id' => $dataAttribute['id'],
                                    'value' => $dataAttributeValue['value']
                                ]);

                            $idArray = isset($attributeValue) ? $attributeValue->id : $dataAttributeValue['id'];
                            array_push($arrayAttributeValues, $idArray);
                        }
                    }

                    foreach ($this->request['variations'] as $dataVariation) {
                        $variation = $this->variationModel::create([
                            "product_id" => $this->request->id,
                            "sku" => $dataVariation['sku'] ?? '',
                            "barcode" => $dataVariation['barcode'],
                            "variation_name" => $dataVariation['variation_name'],
                            "display_name" => $dataVariation['display_name'],
                            "image" => $dataVariation['image'],
                            "price_import" => $dataVariation['price_import'],
                            "price_export" => $dataVariation['price_export'],
                            "status" => $dataVariation['status']
                        ]);

                        array_push($arrayVariations, $variation->id);
                    }

                    foreach ($arrayVariations as $keyVariation => $valueVariation) {
                        foreach ($arrayAttributeValues as $keyAttributeValue => $valueAttributeValue) {
                            $findVariationAttributeValue = $this->variationAttributeModel::query()
                                ->where('variation_id', $valueVariation)
                                ->where('attribute_value_id', $valueAttributeValue)
                                ->first();

                            !isset($findVariationAttributeValue) && $this->variationAttributeModel::create([
                                'variation_id' => $valueVariation,
                                'attribute_value_id' => $valueAttributeValue
                            ]);
                        }
                    }
                }
            } else {
                if ($this->request['variations']) {
                    foreach ($this->request['variations'] as $dataVariation) {
                        $dataUpdate = [
                            "barcode" => $dataVariation['barcode'],
                            "variation_name" => $dataVariation['variation_name'],
                            "display_name" => $dataVariation['display_name'],
                            "image" => $dataVariation['image'],
                            "price_import" => $dataVariation['price_import'],
                            "price_export" => $dataVariation['price_export'],
                            "status" => $dataVariation['status']
                        ];
                        if (isset($dataVariation['sku'])) $dataUpdate['sku'] = $dataVariation['sku'];
                        $this->variationModel::find($dataVariation['id'])->update($dataUpdate);
                    }
                }
            }

            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/products/delete
     * @method POST
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function delete()
    {
        try {
            $arrayIdVariationAttribute = [];
            $variationAttribute = $this->productModel::query()
                ->select('variation_attributes.variation_id')
                ->join('variations', 'products.id', '=', 'variations.product_id')
                ->join('variation_attributes', 'variations.id', '=', 'variation_attributes.variation_id')
                ->where('products.id', $this->request->id)
                ->get();

            foreach ($variationAttribute as $v) {
                array_push($arrayIdVariationAttribute, $v->variation_id);
            }

            $this->variationAttributeModel::whereIn('variation_id', $arrayIdVariationAttribute)->delete();
            $this->variationQuantityModel::whereIn('variation_id', $arrayIdVariationAttribute)->delete();

            $product = $this->productModel::find($this->request->id);

            $product->attributes()->each(function ($attribute) {
                $attribute->attributeValues()->delete();
                $attribute->delete();
            });

            $product->variations()->each(function ($variation) {
                $variation->attributeValues()->each(function ($variationAttribute) {
                    $this->variationAttributeModel::where('id', $variationAttribute->id)->delete();
                });
                $variation->delete();
            });

            $product->delete();
            return responseApi("Xóa thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
