<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StatisticRequest;
use App\Models\Tenant\OrderDetail;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Order;

class StatisticController extends Controller
{
    public function __construct(
        private Order $orderModel,
        private OrderDetail $orderDetailModel,
        private Payment $paymentModel,
        private StatisticRequest $request
    ) {
    }

    public function income(){
        try {
            $data = $this->orderModel::query()->income(
                [
                    $this->request->option,
                    $this->request->start_date,
                    $this->request->end_date],
                $this->request->location_id);

            return responseApi($data, true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function products(){
        try {
            $productData = $this->orderDetailModel::query()->whereProduct([
                $this->request->option,
                $this->request->start_date,
                $this->request->end_date
            ],
                $this->request->location_id);

            $data = $productData->map(function ($productData){
                return [
                    'variation_id' => $productData->variation_id,
                    'sku' => $productData->variant->sku??null,
                    'variation_name' => $productData->variant->variation_name??'Sản phẩm không tồn tại hoặc đã bị xóa',
                    'total_quantity' => intval($productData->total_quantity),
                    'total_price_sell' => $productData->total_price_sell,
                    'total_price_import' => $productData->total_price_import
                ];
            });

            return responseApi($data, true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function general(){
        try {
            return $this->orderModel::query()->general();
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function paymentMethods(){
        try {
            return responseApi($this->paymentModel->paymentMethod(
                    [
                        $this->request->option,
                        $this->request->start_date,
                        $this->request->end_date
                    ],
                    $this->request->location_id
                ), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function customers(){
        try {
            $customerData = $this->orderModel::query()->whereCustomer([
                $this->request->option,
                $this->request->start_date,
                $this->request->end_date
            ],
                $this->request->location_id);

            $data = $customerData->map(function ($customerData){
                return [
                    'customer_id' => $customerData->customer_id??null,
                    'total_bill' => $this->orderModel::query()->countBillCustomer($customerData->customer_id),
                    'total_product' => intval($customerData->total_product),
                    'total_price' => $customerData->total_price,
                    'name' => $customerData->customer->name??'Khách hàng đã bị xóa',
                    'email' => $customerData->customer->email??null,
                    'tel' => $customerData->customer->tel??null
                ];
            });

            return responseApi($data, true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
