<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Debt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Tenant\PaymentRequest;

class PaymentController extends Controller
{
    public function __construct(
        protected Payment $model,
        protected Debt    $debtModel,
        protected Order   $orderModel,
    )
    {
    }

    /**
     * @path /tenant/api/v1/payment
     * @desciption Danh sách thanh toán
     * @method POST
     * @param PaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function index()
    {
        try {
            $payment = $this->model::with('paymentable', 'createdBy')->orderBy('id', 'desc')->get();
            $data = $payment->map(function ($item) {
                return [
                    "id" => $item->id,
                    "paymentable_type" => $item->paymentable_type,
                    "paymentable_id" => $item->paymentable_id,
                    "amount" => $item->amount,
                    "amount_in" => $item->amount_in,
                    "amount_refund" => $item->amount_refund,
                    "payment_method" => $item->payment_method,
                    "payment_at" => Carbon::make($item->payment_at)->format('d-m-Y'),
                    "reference_code" => $item->reference_code,
                    "note" => $item->note,
                    "created_by" => $item->createdBy->name,
                    "created_at" => $item->created_at,
                    "paymentable" => collect($item->paymentable)->merge(['partner_name' => $item->paymentable->partner->name])->all(),
                ];
            });
            return responseApi(paginateCustom($data, $payment), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/payment/debt
     * @desciption Thanh toán công nợ
     * @method POST
     * @param PaymentRequest $request [id, amount, amount_in, amount_refund, payment_method, payment_at, reference_code, note]
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function storeDebt(PaymentRequest $request)
    {
        try {
            $debt = $this->debtModel::find($request->id);
            $amount = $debt->payments()->sum('amount');
            if (($debt->amount_debt - $amount) < $request->amount) return responseApi("Số tiền thanh toán không được lớn hơn số tiền nợ", false);
            $payment = $debt->payments()->create([
                'amount' => $request->amount,
                'amount_in' => $request->amount_in,
                'amount_refund' => $request->amount_refund,
                'payment_method' => $request->payment_method,
                'payment_at' => now(),
                'reference_code' => $request->reference_code,
                'note' => $request->note,
                'created_by' => 1
            ]);
            return responseApi($payment->id, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/payment/order
     * @desciption Thanh toán đơn hàng
     * @method POST
     * @param PaymentRequest $request [id, amount, amount_in, amount_refund, payment_method, payment_at, reference_code, note]
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function storeOrder(Request $request)
    {
        try {
            $order = $this->orderModel::find($request->order_payment[0]['paymentable_id']);
            $paymentOrder = collect($request->order_payment);
            if ($paymentOrder->count() == 1) {
                $amount = $paymentOrder[0]['amount'];
                $amount_in = $paymentOrder[0]['amount_in'];
            }
            foreach ($paymentOrder as $item) {
                $payment = $order->payments()->create([
                    'amount' => $amount ?? $item['pricePayment'],
                    'amount_in' => $amount_in ?? $item['pricePayment'],
                    'amount_refund' => $item['amount_refund'],
                    'payment_method' => $item['payment_method'],
                    'payment_at' => now(),
                    'reference_code' => $item['reference_code'] ?? null,
                    'note' => $item['note'],
                    'created_by' => 1
                ]);
            }
            if ($payment) return responseApi("Thêm thành công", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
