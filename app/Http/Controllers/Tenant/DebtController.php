<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DebtRequest;
use Carbon\Carbon;
use App\Models\Tenant\Debt;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    //
    public function __construct(
        private Debt        $model,
        private DebtRequest $request
    )
    {
        $this->checkTimeDebt();
        $this->checkAmountDebt();
    }

    /**
     * @path /tenant/api/v1/debt
     * @method POST
     * @param DebtRequest $request type||null
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        try {
            $debt = $this->model::with(['partner','location'])
                ->when($request->has('location_id'), function ($query) use ($request) {
                   return $query->where('location_id', $request->location_id);
                })
                ->orderBy('id', 'desc')
                ->get();
            if ($request->has('type')) {
                $debt = $this->model::with('partner','location')
                    ->when($request->has('location_id'), function ($query) use ($request) {
                        return $query->where('location_id', $request->location_id);
                    })
                    ->where('type', $request->type)
                    ->orderBy('id', 'desc')
                    ->get();
            }
            $data = $debt->map(function ($item) {
                return [
                    "id" => $item->id,
                    "partner_name" => $item->partner->name,
                    "debit_at" => $item->debit_at,
                    "due_at" => $item->due_at,
                    "type" => $item->type,
                    "name" => $item->name,
                    "amount_debt" => $item->amount_debt,
                    "amount_paid" => $item->amount_paid,
                    "note" => $item->note,
                    "status" => $item->status,
                    "location_id" => $item->location_id,
                    "location_name" => $item->location->name??"Chi nhánh không tồn tại hoặc đã bị xoá"
                ];
            });
            return responseApi($data, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/debt/store
     * @method POST
     * @param DebtRequest $request
     * @requires partner_id,partner_type,debit_at,due_at,type,name,amount_debt,amount_paid,note
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store()
    {
        try {
            if ($this->request->has('due_at') && $this->request->has('debit_at')
                && $this->request->due_at != "" && $this->request->debit_at != "") {
                if (Carbon::make($this->request->due_at)->timestamp < Carbon::make($this->request->debit_at)->timestamp) {
                    return responseApi("Ngày đáo hạn không được nhỏ hơn ngày nợ!", false);
                }
            }
            $debt = $this->model::create([
                "partner_id" => $this->request->partner_id,
                "partner_type" => $this->request->partner_type,
                "debit_at" => $this->request->debit_at ? Carbon::make($this->request->debit_at)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                "due_at" => $this->request->due_at ? Carbon::make($this->request->due_at)->format('Y-m-d') : ($this->request->debit_at ? Carbon::make($this->request->debit_at)->addYear()->format('Y-m-d') : Carbon::now()->addYear()->format('Y-m-d')),
                "type" => $this->request->type,
                "name" => $this->request->name,
                "amount_debt" => $this->request->amount_debt,
                "amount_paid" => $this->request->amount_paid,
                "note" => $this->request->note,
                "status" => 1,
                "location_id" => $this->request->location_id
            ]);
            return responseApi($debt, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/debt/show
     * @method POST
     * @param DebtRequest $request
     * @requires id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function show()
    {
        try {
            $debt = $this->model::with('partner','location')->where('id', $this->request->id)->get();
            $response = $debt->map(function ($item) {
                return [
                    "id" => $item->id,
                    "partner_id" => $item->partner->id,
                    "partner_type" => $item->partner_type,
                    "partner_name" => $item->partner->name,
                    "debit_at" => $item->debit_at,
                    "due_at" => $item->due_at,
                    "type" => $item->type,
                    "name" => $item->name,
                    "amount_debt" => $item->amount_debt,
                    "amount_paid" => $item->amount_paid,
                    "note" => $item->note,
                    "status" => $item->status,
                    "location_id" => $item->location_id,
                    "location_name" => $item->location->name??"Chi nhánh không tồn tại hoặc đã bị xoá",
                    "payments" => $item->payments
                ];
            });
            return responseApi(collect($response)->collapse(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/debt/update
     * @method POST
     * @param DebtRequest $request
     * @requires partner_id,partner_type,debit_at,due_at,type,name,amount_debt,amount_paid,note,status
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update()
    {
        try {
            if ($this->request->has('due_at') && $this->request->has('debit_at')
                && $this->request->due_at != "" && $this->request->debit_at != "") {
                if (Carbon::make($this->request->due_at)->timestamp < Carbon::make($this->request->debit_at)->timestamp) {
                    return responseApi("Ngày đáo hạn không được nhỏ hơn ngày nợ!", false);
                }
            }
            $debt = $this->model::find($this->request->id)->update([
                "partner_id" => $this->request->partner_id,
                "partner_type" => $this->request->partner_type,
                "debit_at" => $this->request->debit_at ? Carbon::make($this->request->debit_at)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                "due_at" => $this->request->due_at ? Carbon::make($this->request->due_at)->format('Y-m-d') : ($this->request->debit_at ? Carbon::make($this->request->debit_at)->addYear()->format('Y-m-d') : Carbon::now()->addYear()->format('Y-m-d')),
                "type" => $this->request->type,
                "name" => $this->request->name,
                "amount_debt" => $this->request->amount_debt,
                "amount_paid" => $this->request->amount_paid,
                "note" => $this->request->note,
                "status" => $this->request->status,
                "location_id" => $this->request->location_id
            ]);
            return responseApi($debt, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/debt/delete
     * @method POST
     * @param DebtRequest $request
     * @requires partner_id,partner_type,debit_at,due_at,type,name,amount_debt
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function delete()
    {
        try {
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function checkTimeDebt()
    {
        $now = Carbon::now()->format('Y-m-d');
        try {
            $this->model::where('due_at', '<', $now)->update(['status' => 0]);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function checkAmountDebt()
    {
        try {
            $debt = $this->model::where('status', '<>',3)->get();
            $debt->map(function ($item) {
                $amount_paid = $item->payments->sum('amount');
                if ($item->payments->sum('amount') > 0) {
                    $item->update(['amount_paid' => $amount_paid, 'status' => 2]);
                }
                if ($item->amount_debt == $amount_paid) {
                    $item->update(['status' => 3]);
                }
            });
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
