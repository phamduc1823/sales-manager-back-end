<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Order extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'orders';
    protected $fillable=[
        "location_id",
        "customer_id",
        "created_by",
        "discount",
        "discount_type",
        "quantity",
        "tax",
        "service_charge",
        "total_product",
        "total_price",
        "status",
        "payment_status"
    ];
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class,'order_id','id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class,'location_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function orderReturns()
    {
        return $this->hasMany(OrderReturn::class,'order_id','id');
    }
    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function scopeGeneral($query){
        $total_price = $query->where('payment_status', 2)->whereDate('created_at', Carbon::today())->sum('total_price');
        $order_completed = $query->whereDate('created_at', Carbon::today())->count();

        return responseApi([
            'total_price' => $total_price,
            'order_completed' => $order_completed
        ], true);
    }

    public function scopeIncome($query, array $option = [], ?int $locationId = 0){
        $query->when($locationId != 0, function ($query) use ($locationId){
            return $query->where('location_id', $locationId);
        })->where('payment_status', 2);

        switch ($option[0]){
            case 'today':
                return [
                    'title' => [Carbon::today()->format('d/m/Y')],
                    'data' => [
                        $query->whereDate('created_at', Carbon::today())->sum('total_price')
                    ]
                ];
            case 'yesterday':
                return [
                    'title' => [Carbon::yesterday()->format('d/m/Y')],
                    'data' => [
                        $query->whereDate('created_at', Carbon::yesterday())->sum('total_price')
                    ]
                ];
            case 'sevenDays':
                $last7Days = [];
                $last7DaysData = [];

                for ($i = 6; $i >= 0; $i--) {
                    $last7Days[] = Carbon::now()->subDays($i)->format('d/m/Y');
                    $last7DaysData[] = $this->when($locationId != 0, function ($query) use ($locationId){
                            return $query->where('location_id', $locationId);
                        })->where('payment_status', 2)
                        ->whereDate('created_at', '=', Carbon::now()->subDays($i))
                        ->sum('total_price');
                }

                return [
                    'title' => $last7Days,
                    'data' => $last7DaysData
                ];

            case 'thirtyDays':
                $last30Days = [];
                $last30DaysData = [];

                for ($i = 29; $i >= 0; $i--) {
                    $last30Days[] = Carbon::now()->subDays($i)->format('d/m/Y');
                    $last30DaysData[] = $this->when($locationId != 0, function ($query) use ($locationId){
                        return $query->where('location_id', $locationId);
                    })->where('payment_status', 2)
                        ->whereDate('created_at', '=', Carbon::now()->subDays($i))
                        ->sum('total_price');
                }

                return [
                    'title' => $last30Days,
                    'data' => $last30DaysData
                ];

            case 'fromTo':
                $dateRange = CarbonPeriod::create($option[1], $option[2]);
                $allDates = collect($dateRange)->map(function ($date) {
                    return $date->format('d/m/Y');
                });

                $specifiedRange = $this::query()
                    ->when($locationId != 0, function ($query) use ($locationId){
                        return $query->where('location_id', $locationId);
                    })->where('payment_status', 2)
                    ->whereBetween('created_at', [$option[1].' 00:00:00', $option[2].' 23:59:59'])
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy(DB::raw('DATE(created_at)'))
                    ->select([
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('SUM(total_price) as total_price'),
                    ])
                    ->get();

                $result = $allDates->map(function ($date) use ($specifiedRange) {
                    $foundDate = $specifiedRange->firstWhere('date', Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d'));
                    return [
                        'date' => $date,
                        'total_price' => $foundDate ? $foundDate->total_price : 0,
                    ];
                });

                return [
                    'title' => $result->pluck('date')->toArray(),
                    'data' => $result->pluck('total_price')->toArray(),
                ];
            default:
                return responseApi("Lỗi", false);
        }
    }

    public function scopeCountBillCustomer($query, $customerId){
        return $query->where('customer_id', $customerId)->count();
    }

    public function scopeWhereCustomer($query, array $option = [], ?int $locationId = 0){
        $query->select('customer_id',
            DB::raw('sum(total_product) as total_product'),
            DB::raw('sum(total_price) as total_price'))
            ->with(['customer'])
            ->where('payment_status', 2)
            ->when($locationId != 0, function ($query) use ($locationId){
                return $query->where('location_id', $locationId);
            })
            ->groupBy('customer_id');

        switch ($option[0]){
            case 'today':
                return $query->whereDate('created_at', Carbon::today())->get();
            case 'yesterday':
                return $query->whereDate('created_at', Carbon::yesterday())->get();
            case 'sevenDays':
                return $query->whereDate('created_at', '>=', Carbon::now()->subDays(7))->get();
            case 'thirtyDays':
                return $query->whereDate('created_at', '>=', Carbon::now()->subDays(30))->get();
            case 'fromTo':
                return $query->whereBetween('created_at', [$option[1].' 00:00:00', $option[2].' 23:59:59'])->get();
            default:
                return responseApi("Lỗi", false);
        }
    }
}
