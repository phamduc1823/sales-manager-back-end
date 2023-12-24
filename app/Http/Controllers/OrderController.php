<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SubscriptionOrder;
use App\Models\SubscriptionOrderNote;
use App\Models\TenantChangeHistory;
use App\Models\Order;
use App\Models\Pricing;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function index()
    {
        $tenants = Tenant::get();
        $pricings = Pricing::get();
        $users = User::get();
        $data = SubscriptionOrder::with('tenant:id,business_name,due_at', 'pricing:id,name', 'assignedTo:id,name')->orderBy('created_at')->get();
        $subscriptionOrder = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'tenant' => $item->tenant?->business_name,
                'due_at' => $item->tenant?->due_at,
                'pricing' => $item->pricing->name,
                'type' => $item->type,
                'name' => $item->name,
                'tel' => $item->tel,
                'assigned_to' => $item->assignedTo?->name,
                'status' => $item->status,
                'status_name' => $this->checkStatus($item->status),
                'created_at' => $item->created_at->format('Y-m-d H:i'),
            ];
        });
        return view('admin.order.index', compact('subscriptionOrder', 'tenants', 'pricings', 'users'));
    }

    public function show(Request $request)
    {
        $subscriptionOrder = SubscriptionOrder::with('tenant:id,business_name', 'pricing:id,name', 'assignedTo:id,name')->where('id', $request->id)->get();
        $notes = SubscriptionOrderNote::where('subscription_order_id', $request->id)->first()?->note;
        $data = $subscriptionOrder->map(function ($item) {
            return [
                'id' => $item->id,
                'tenant' => $item->tenant->business_name,
                'pricing' => $item->pricing->name,
                'type' => $item->type,
                'name' => $item->name,
                'tel' => $item->tel,
                'assigned_to' => $item->assignedTo->id ?? null,
                'status_name' => $this->checkStatus($item->status),
                'created_at' => $item->created_at->format('Y-m-d H:i'),
            ];
        });
        return response()->json(['detail' => collect($data)->collapse(), 'notes' => $notes]);
    }

    public function checkStatus($status)
    {
        if ($status == 0) {
            return 'Huỷ';
        } elseif ($status == 1) {
            return 'Chưa xử lý';
        } elseif ($status == 2) {
            return 'Đang xử lý';
        } elseif ($status == 3) {
            return 'Đã xử lý';
        } else {
            return 'Không xác định';
        }
    }

    public function updateStatus(Request $request)
    {
        $order = SubscriptionOrder::findOrFail($request->id);
        if ($order->status == 3 || $order->status == 0) {
            return response()->json(['msg' => 'Không thể thay đổi trạng thái khi đơn đã hoàn thành hoặc huỷ', 'status' => 200]);
        }
        if ($request->status == 2) {
            $tenant = Tenant::findOrFail($order->tenant_id);
            $price = Pricing::findOrFail($order->pricing_id)?->price;
            TenantChangeHistory::create([
                'tenant_id' => $order->tenant_id,
                'change_type' => $order->type,
                'from_pricing_id' => $tenant->pricing_id,
                'to_pricing_id' => $order->pricing_id,
                'total_price' => $price,
                'created_by' => auth()->user()->id
            ]);
        } elseif ($request->status == 0) {
            $tenantChangeHistory = TenantChangeHistory::where('tenant_id', $order->tenant_id);
            if ($tenantChangeHistory) $tenantChangeHistory->delete();
        }elseif ($request->status==3){
            $tenantChangeHistory=TenantChangeHistory::where('tenant_id',$order->tenant_id)->first()?->id;
            $order=Order::where('tenant_change_history_id',$tenantChangeHistory)->count();
            if ($order==0){
                return response()->json(['msg' => 'Thanh toán đơn hàng trước khi thay dổi trạng thái', 'status' => 200]);
            }
        }
        $order->update([
            'status' => $request->status,
        ]);
        return response()->json(['msg' => 'Cập nhật trạng thái thành công!', 'status' => 200]);
    }

    public function updateAssignedTo(Request $request)
    {
        $order = SubscriptionOrder::findOrFail($request->id);
        $order->update([
            'assigned_to' => $request->assigned_to,
        ]);
        return response()->json(['success' => 'Cập nhật thành công!', 'status' => 200]);
    }

    public function store(OrderRequest $request)
    {
        try {
            $tenant = Tenant::where('id', $request->tenant_id)->first()?->pricing_id;
            if ($request->type == 0 && $tenant == $request->pricing_id) {
                return responseApi('Bạn đã đăng ký gói này rồi! Không thể thay đổi gói', false);
            }
            $order = SubscriptionOrder::create([
                'tenant_id' => $request->tenant_id,
                'pricing_id' => $request->pricing_id,
                'type' => $request->type,
                'name' => $request->name,
                'tel' => $request->tel,
                'assigned_to' => auth()->user()->id,
                'status' => 2,
            ]);
            if ($request->has('note') && $request->note != '' && $order) {
                SubscriptionOrderNote::create([
                    'subscription_order_id' => $order->id,
                    'note' => $request->note,
                    'created_by' => auth()->user()->id
                ]);
            }
            $tenant = Tenant::findOrFail($request->tenant_id);
            $price = Pricing::findOrFail($request->pricing_id)?->price;
            TenantChangeHistory::create([
                'tenant_id' => $request->tenant_id,
                'change_type' => $request->type,
                'from_pricing_id' => $tenant->pricing_id,
                'to_pricing_id' => $request->pricing_id,
                'total_price' => $price,
                'created_by' => auth()->user()->id
            ]);
            return responseApi('Tạo yêu cầu thành công', true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete(Request $request)
    {
        try {
            $subscriptionOrder = SubscriptionOrder::findOrFail($request->id);
            if (!$subscriptionOrder) return response()->json(['error' => 'Không tìm thấy đơn hàng!']);
            $subscriptionOrder->delete();
            $subscriptionOrderNote = SubscriptionOrderNote::where('subscription_order_id', $request->id);
            if ($subscriptionOrderNote) $subscriptionOrderNote->delete();
            $tenantChangeHistory = TenantChangeHistory::where('tenant_id', $subscriptionOrder->tenant_id);
            if ($tenantChangeHistory) $tenantChangeHistory->delete();
            return response()->json(['msg' => 'Xóa thành công!', 'status' => 200]);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()]);
        }
    }

    public function createNote(Request $request)
    {
        $check = SubscriptionOrderNote::where('subscription_order_id', $request->subscription_order_id)->first();
        if ($check) {
            $check->update([
                'note' => $request->note,
                'created_by' => auth()->user()->id
            ]);
            return response()->json(['success' => 'Cập nhật ghi chú thành công!']);
        }

        SubscriptionOrderNote::create([
            'subscription_order_id' => $request->subscription_order_id,
            'note' => $request->note,
            'created_by' => auth()->user()->id
        ]);
        return response()->json(['success' => 'Thêm ghi chú thành công!']);
    }

    public function showNote(Request $request)
    {
        $notes = SubscriptionOrderNote::where('subscription_order_id', $request->subscription_order_id)->first()?->note;
        return response()->json(['notes' => $notes]);
    }

    public function listTenantChangeHistory(Request $request)
    {
        $tenantChangeHistory = TenantChangeHistory::query()
            ->with('tenant:id,business_name', 'fromPricing:id,name', 'toPricing:id,name', 'createdBy:id,name', 'order:id,tenant_change_history_id')
            ->orderBy('created_at')
            ->get();
        $data = $tenantChangeHistory->map(function ($item) {
            return [
                'id' => $item->id,
                'tenant' => $item->tenant->business_name,
                'change_type' => $item->change_type,
                'from_pricing' => $item->fromPricing->name,
                'to_pricing' => $item->toPricing->name,
                'total_price' => $item->total_price,
                'status' => $item->order->count() > 0 ? 0 : 1,
                'created_by' => $item->createdBy->name,
                'created_at' => $item->created_at->format('Y-m-d H:i'),
            ];
        });
        return view('admin.order.request', compact('data'));
    }

    public function showTenantChangeHistory(Request $request)
    {
        $tenantChangeHistory = TenantChangeHistory::query()
            ->where('id', $request->id)
            ->first();
        return response()->json(['detail' => $tenantChangeHistory]);
    }

    public function storeOrder(Request $request)
    {
        try {
            $tenantChangeHistory = TenantChangeHistory::query()
                ->where('id', $request->id)
                ->first();
            $order = Order::create([
                'tenant_change_history_id' => $request->id,
                'total' => $tenantChangeHistory->total_price,
                'payment_method' => $request->payment_method,
                'reference_code' => $request->reference_code,
                'created_by' => auth()->user()->id,
            ]);
            if ($order) {
                SubscriptionOrder::where('tenant_id', $tenantChangeHistory->tenant_id)->update([
                    'status' => 3,
                ]);
                $expiryDay = Pricing::where('id', $tenantChangeHistory->to_pricing_id)->first()?->expiry_day;
                $changType = $tenantChangeHistory->change_type;
                if ($changType == 0) {
                    $dueAt = Carbon::now()->addDays($expiryDay)->format('Y-m-d');
                } elseif ($changType == 1) {
                    $dueAtCurrent = Tenant::where('id', $tenantChangeHistory->tenant_id)->first()?->due_at;
                    $dueAt = Carbon::parse($dueAtCurrent)->addDays($expiryDay)->format('Y-m-d');
                } else {
                    $dueAt = Carbon::now()->addDays($expiryDay)->format('Y-m-d');
                }
                Tenant::where('id', $tenantChangeHistory->tenant_id)->update([
                    'pricing_id' => $tenantChangeHistory->to_pricing_id,
                    'due_at' => $dueAt,
                ]);
                Cache::flush();
            }
            return response()->json(['success' => 'Tạo thành công!', 'order' => $order]);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()]);
        }
    }

    public function storeSubscriptionOrderApi(OrderRequest $request)
    {
        try {
            $tenant = Tenant::where('id', $request->tenant_id)->first()?->pricing_id;
            if ($request->type == 0 && $tenant == $request->pricing_id) {
                return responseApi('Bạn đã đăng ký gói này rồi! Không thể thay đổi gói', false);
            }
            $order = SubscriptionOrder::create([
                'tenant_id' => $request->tenant_id,
                'pricing_id' => $request->pricing_id,
                'type' => $request->type,
                'name' => $request->name,
                'tel' => $request->tel,
                'assigned_to' => null,
                'status' => 1,
            ]);
            return responseApi('Tạo thành công mã đơn ' . $order->id . '!', true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
