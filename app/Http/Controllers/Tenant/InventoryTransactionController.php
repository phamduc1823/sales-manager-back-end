<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Tenant\InventoryTransaction;
use App\Models\Tenant\VariationQuantity;
use App\Models\Tenant\Variation;
use App\Http\Requests\Tenant\InventoryTransactionRequest;
use App\Http\Requests\Tenant\VariationQuantityRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    public function __construct(
        private InventoryTransaction $model,
        private VariationQuantity    $variationQuantityModel,
        protected Variation          $variationModel
    )
    {
    }

    /**
     * @path /tenant/api/v1/storage/import/create
     * @desciption tạo đơn nhập kho
     * @method POST
     * @param InventoryTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(InventoryTransactionRequest $request)
    {
        DB::beginTransaction();
        try {
            $inventory_transaction_id = Carbon::now()->timestamp;
            $inventoryTransaction = $this->model::create([
                "inventory_id" => $request->inventory_id,
                "partner_id" => $request->partner_id,
                "partner_type" => $request->partner_type,
                "trans_type" => 0,
                "inventory_transaction_id" => $inventory_transaction_id,
                "reason" => $request->reason,
                "note" => $request->note,
                "status" => 1,
                "created_by" => $request->created_by
            ]);
            $inventoryTransaction->inventoryTransactionDetails()->createMany(collect($request->inventory_transaction_details)->toArray());
            DB::commit();
            return responseApi($inventory_transaction_id, true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/storage/import
     * @desciption danh sách đơn nhập kho
     * @method POST
     * @param InventoryTransactionRequest $request trans_type||null
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function list(Request $request)
    {
        try {
            $inventoryTransactionData = $this->model::with('inventory', 'partner', 'createdBy')
                ->when($request->inventory_id, function ($query) use ($request) {
                   return $query->where('inventory_id', $request->inventory_id);
            })->orderBy('created_at', 'desc')->get();
            if ($request->has('trans_type') && $request->trans_type != '') {
                $inventoryTransactionData = $this->model::with('inventory', 'partner', 'createdBy')
                    ->when($request->inventory_id, function ($query) use ($request) {
                       return $query->where('inventory_id', $request->inventory_id);
                    })
                    ->where('trans_type', $request->trans_type)
                    ->orderBy('created_at', 'desc')->get();
            }
            $data = $inventoryTransactionData->map(function ($inventoryTransactionData) {
                return [
                    "inventory_transaction_id" => $inventoryTransactionData->inventory_transaction_id,
                    "partner_name" => $inventoryTransactionData->partner->name ?? "Đơn chuyển kho",
                    "inventory_name" => $inventoryTransactionData->inventory->name ?? null,
                    "created_by" => $inventoryTransactionData->createdBy->name,
                    "status" => $inventoryTransactionData->status,
                    "created_at" => Carbon::make($inventoryTransactionData->created_at)->format('H:i d-m-Y'),
                    "updated_at" => Carbon::make($inventoryTransactionData->updated_at)->format('H:i d-m-Y'),
                ];
            });
            return responseApi($data, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/storage/import/{id}
     * @desciption chi tiết đơn nhập kho
     * @method POST
     * @param InventoryTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function show($id)
    {
        try {
            if ($this->model::where('inventory_transaction_id', $id)->count() == 3) {
                $inventoryTransactionData = $this->model::with('inventoryTransactionDetails', 'inventory:id,name', 'inventoryOut:id,name', 'partner', 'createdBy', 'inventoryTransactionDetails.variation:id,variation_name,sku')->where("inventory_transaction_id", $id)->where('trans_type', 2)->get();
            } else {
                $inventoryTransactionData = $this->model::with('inventoryTransactionDetails', 'inventory', 'partner', 'createdBy', 'inventoryTransactionDetails.variation:id,variation_name,sku')->where("inventory_transaction_id", $id)->where('trans_type', 0)->get();
            }
            $data = $inventoryTransactionData->map(function ($inventoryTransactionData) {
                return [
                    "id" => $inventoryTransactionData->id,
                    "inventory_id" => $inventoryTransactionData->inventory->id ?? null,
                    "inventory_name" => $inventoryTransactionData->inventory->name ?? null,
                    "inventory_id_out" => $inventoryTransactionData->inventoryOut->id ?? null,
                    "inventory_name_out" => $inventoryTransactionData->inventoryOut->name ?? null,
                    "partner_name" => $inventoryTransactionData->partner->name ?? null,
                    "partner_type" => $inventoryTransactionData->partner_type ?? null,
                    "trans_type" => $inventoryTransactionData->trans_type,
                    "inventory_transaction_id" => $inventoryTransactionData->inventory_transaction_id,
                    "reason" => $inventoryTransactionData->reason,
                    "note" => $inventoryTransactionData->note,
                    "status" => $inventoryTransactionData->status,
                    "created_by" => $inventoryTransactionData->createdBy->name,
                    "inventory_transaction_details" => $inventoryTransactionData->inventoryTransactionDetails->map(function ($inventoryTransactionDetails) {
                        return [
                            "variation_name" => $inventoryTransactionDetails->variation->variation_name ?? null,
                            "sku" => $inventoryTransactionDetails->variation->sku ?? null,
                            "batch_id" => $inventoryTransactionDetails->batch_id,
                            "quantity" => $inventoryTransactionDetails->quantity,
                            "price" => $inventoryTransactionDetails->price,
                        ];
                    }),
                    "created_at" => Carbon::make($inventoryTransactionData->created_at)->format('H:i d-m-Y'),
                    "updated_at" => Carbon::make($inventoryTransactionData->updated_at)->format('H:i d-m-Y'),
                ];
            });
            return responseApi($data, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/storage/update
     * @desciption cập nhật trạng thái hoàn thành đơn nhập kho, xuất kho và cập nhật số lượng tồn kho
     * @method POST
     * @param Request $request [id,tranType]
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($this->model::where('inventory_transaction_id', $request->id)->count() == 0) {
                return responseApi("Không tìm thấy đơn hàng!", false);
            }
            $inventoryTransaction = $this->model::with('inventoryTransactionDetails')->where('inventory_transaction_id', $request->id)->where('trans_type', $request->tranType);
            $inventoryTransaction = $inventoryTransaction->first();
            if ($inventoryTransaction->status === 2) {
                return responseApi("Đơn hàng đã được cập nhật trạng thái!", false);
            }

            if ($request->tranType == 0) {
                if ($this->checkStatusTransfer($request->id)) {
                    return responseApi("Đơn xuất chưa được xử lý!", false);
                }
                $inventoryTransaction->update(["status" => 2]);
                $this->updateStatusTransfer($request->id);
            } else if ($request->tranType == 1) {
                $inventoryTransaction->update(["status" => 2]);
            } else {
                return responseApi("Trạng thái không hợp lệ!", false);
            }
            foreach ($inventoryTransaction->inventoryTransactionDetails as $item => $value) {
                $variationQuantity = $this->variationQuantityModel::where('variation_id', $value->variation_id)->where('inventory_id', $inventoryTransaction->inventory_id);
                $variationQuantityData = $variationQuantity->first();
                if ($request->tranType == 0) {
                    if ($variationQuantityData) {
                        $variationQuantity->increment('quantity', $value->quantity);
                    } else {
                        VariationQuantity::create([
                            'variation_id' => $value->variation_id,
                            'inventory_id' => $inventoryTransaction->inventory_id,
                            'batch_id' => $value->batch_id,
                            'price_import' => $value->price,
                            'quantity' => $value->quantity,
                        ]);
                    }
                } else {
                    if ($variationQuantityData->quantity < $value->quantity) {
                        return responseApi("Số lượng tồn kho không đủ!", false);
                    }
                    $variationQuantity->decrement('quantity', $value->quantity);
                }
            }
            DB::commit();
            $message = $request->tranType == 0 ? "Cập nhật thành công đơn nhập" : "Cập nhật thành công đơn xuất" . $inventoryTransaction->inventory_transaction_id;
            return responseApi($message, true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/storage/import/cancel/{id}
     * @desciption huỷ đơn nhập kho
     * @method POST
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function cancel(Request $request, $id)
    {
        try {
            $query = $this->model::where("inventory_transaction_id", $id);
            if ($query->count() == 0) {
                return responseApi("Không tìm thấy đơn hàng!", false);
            } elseif ($query->count() == 3) {
                if ($request->trans_type == 2) {
                    $query->update(["status" => 0]);
                    return responseApi("Huỷ thành công!", true);
                } else {
                    if ($request->trans_type == 1) {
                        $query->update(["status" => 0]);
                        return responseApi("Huỷ thành công!", true);
                    } else {
                        return responseApi("Đơn nhập kho không thể huỷ khi đơn chuyển chưa hoàn thành!", false);
                    }
                }
            } else {
                $query->update(["status" => 0]);
                return responseApi("Huỷ thành công!", true);
            }
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/storage/update-quantity/{inventoryId}
     * @desciption cập nhật số lượng tồn kho
     * @method POST
     * @param VariationQuantityRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function updateQuantity(VariationQuantityRequest $request, $inventoryId)
    {
        try {
            $variationQuantity = $this->variationQuantityModel::where('inventory_id', $inventoryId)
                ->where('variation_id', $request->variation_id)
                ->first();
            $priceImport = $this->variationModel::findOrfail($request->variation_id)->price_import;
            if (($variationQuantity->quantity * 1 + $request->quantity * 1) < 0) {
                return responseApi("Số lượng tồn kho không đủ!", false);
            }
            if ($variationQuantity) {
                $variationQuantity->increment('quantity', $request->quantity);
            } else {
                $this->variationQuantityModel::create([
                    'variation_id' => $request->variation_id,
                    'inventory_id' => $inventoryId,
                    'batch_id' => $request->batch_id,
                    'price_import' => $priceImport,
                    'quantity' => $request->quantity
                ]);
            }
            return responseApi("Cập nhật thành công", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/storage/trans/store
     * @desciption tạo đơn chuyển kho
     * @method POST
     * @param VariationQuantityRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function createTransfer(InventoryTransactionRequest $request)
    {
        $inventory_transaction_id = Carbon::now()->timestamp;
        $variationIds = collect($request->inventory_transaction_details)->pluck('variation_id')->toArray();
        $requestedQuantities = collect($request->inventory_transaction_details)->pluck('quantity')->toArray();
        if ($request->inventory_id_in == $request->inventory_id_out) return responseApi("Kho xuất và kho nhập không được trùng nhau!", false);
        $filteredQuantity = array_filter($requestedQuantities, function ($value) {
            return $value <= 0;
        });
        if (!empty($filteredQuantity)) return responseApi("Số lượng phải lớn hơn 0!", false);
        $currentQuantities = $this->variationQuantityModel::where('inventory_id', $request->inventory_id_out)
            ->whereIn('variation_id', $variationIds)
            ->get()
            ->pluck('quantity', 'variation_id')
            ->toArray();
        foreach ($variationIds as $index => $variationId) {
            if (!isset($currentQuantities[$variationId]) || $currentQuantities[$variationId] < $requestedQuantities[$index]) {
                return responseApi("Số lượng tồn kho không đủ cho biến thể có id là {$variationId}!", false);
            }
        }
        DB::beginTransaction();
        try {
            $inventoryTransaction = $this->model::create([
                "inventory_id" => $request->inventory_id_in,
                "inventory_id_out" => $request->inventory_id_out,
                "partner_id" => $request->partner_id,
                "partner_type" => $request->partner_type,
                "trans_type" => 2,
                "inventory_transaction_id" => $inventory_transaction_id,
                "reason" => $request->reason,
                "note" => $request->note,
                "status" => 1,
                "created_by" => $request->created_by
            ]);
            $this->model::create([
                "inventory_id" => $request->inventory_id_in,
                "inventory_id_out" => null,
                "partner_id" => $request->partner_id,
                "partner_type" => $request->partner_type,
                "trans_type" => 0,
                "inventory_transaction_id" => $inventory_transaction_id,
                "reason" => $request->reason,
                "note" => $request->note,
                "status" => 1,
                "created_by" => $request->created_by
            ]);
            $this->model::create([
                "inventory_id" => $request->inventory_id_out,
                "inventory_id_out" => null,
                "partner_id" => $request->partner_id,
                "partner_type" => $request->partner_type,
                "trans_type" => 1,
                "inventory_transaction_id" => $inventory_transaction_id,
                "reason" => $request->reason,
                "note" => $request->note,
                "status" => 1,
                "created_by" => $request->created_by
            ]);
            $inventoryTransaction->inventoryTransactionDetails()->createMany(collect($request->inventory_transaction_details)->toArray());
            DB::commit();
            return responseApi($inventory_transaction_id, true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1//storage/trans
     * @desciption danh sách đơn chuyển kho
     * @method POST
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function listTransfer(Request $request)
    {
        try {
            $listTransfer = $this->model::with('inventory', 'inventoryOut', 'inventoryTransactionDetails', 'createdBy:id,name', 'inventory.location:id,name', 'inventoryOut.location:id,name')
                ->when($request->inventory_id, function ($query) use ($request) {
                    return $query->where('inventory_id_out', $request->inventory_id);
                })
                ->where('trans_type', 2)->orderBy('id', 'desc')->get();
            $response = $listTransfer->map(function ($listTransfer) {
                return [
                    "inventory_transaction_id" => $listTransfer->inventory_transaction_id,
                    "inventory_id_in" => $listTransfer->inventory->id,
                    "location_id_in" => $listTransfer->inventory->location->id,
                    "location_name_in" => $listTransfer->inventory->location->name,
                    "inventory_name_in" => $listTransfer->inventory->name,
                    "inventory_id_out" => $listTransfer->inventoryOut->id,
                    "location_id_out" => $listTransfer->inventoryOut->location->id,
                    "inventory_name_out" => $listTransfer->inventoryOut->name,
                    "location_name_out" => $listTransfer->inventoryOut->location->name,
                    "reason" => $listTransfer->reason,
                    "status" => $listTransfer->status,
                    "total_quantity" => $listTransfer->inventoryTransactionDetails->sum('quantity'),
                    "total_variation" => $listTransfer->inventoryTransactionDetails->count(),
                    "note" => $listTransfer->note,
                    "created_by" => $listTransfer->createdBy->name,
                    "created_at" => Carbon::make($listTransfer->created_at)->format('H:i d-m-Y'),
                    "updated_at" => Carbon::make($listTransfer->updated_at)->format('H:i d-m-Y'),
                ];
            });
            return responseApi($response, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @desciption cập nhật trạng thái đơn chuyển kho khi đơn nhập và đơn xuất đã được xử lý
     * @param $inventory_transaction_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    protected function updateStatusTransfer($inventory_transaction_id)
    {
        try {
            $data = $this->model::where('inventory_transaction_id', $inventory_transaction_id);
            $count = $data->count();
            if ($count == 3) {
                $statusIn = $this->model::where('inventory_transaction_id', $inventory_transaction_id)->where('trans_type', 0)->first()->status;
                $statusOut = $this->model::where('inventory_transaction_id', $inventory_transaction_id)->where('trans_type', 1)->first()->status;
                if ($statusIn == 2 && $statusOut == 2) {
                    $data->update(['status' => 2]);
                } else {
                    return responseApi("Đơn chuyển kho chưa được xử lý!", false);
                }
            }
            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @desciption kiểm tra trạng thái đơn xuất kho khi xử lý đơn nhập trong trường hợp chuyển kho
     * @param $inventory_transaction_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    protected function checkStatusTransfer($inventory_transaction_id)
    {
        try {
            $data = $this->model::where('inventory_transaction_id', $inventory_transaction_id);
            $count = $data->count();
            if ($count == 3) {
                $statusOut = $this->model::where('inventory_transaction_id', $inventory_transaction_id)->where('trans_type', 1)->first()->status;
                if ($statusOut == 1 || $statusOut == 0) {
                    return true;
                }
            }
            return false;
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}


