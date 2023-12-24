<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Time;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Payment extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'payments';
    protected $fillable=[
        "paymentable_type",
        "paymentable_id",
        "amount",
        "amount_in",
        "amount_refund",
        "payment_method",
        "payment_at",
        "reference_code",
        "note",
        "created_by"
    ];
    public function paymentable()
    {
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    private function buildQuery(int $payment_method, $time, array $option = [], ?int $locationId = 0)
    {
        $query = $this->when($locationId != 0, function ($query) use ($locationId) {
            return $query->whereHas('paymentable', function ($query) use ($locationId) {
                return $query->where('location_id', $locationId);
            });
        })->where('payment_method', $payment_method);

        if($option[0] == "today" || $option[0] == "yesterday"){
           return $query->whereDate('payment_at', $time);
        }

        if($option[0] == "sevenDays" || $option[0] == "thirtyDays"){
            return $query->whereDate('payment_at', '>=', $time);
        }

        return $query->whereBetween('payment_at', [$option[1].' 00:00:00', $option[2].' 23:59:59']);
    }

    private function returnData($time, array $option = [], ?int $locationId = 0){
        $count = [
            [
                $this->buildQuery(0, $time, $option, $locationId)->sum('amount'),
                $this->buildQuery(0, $time, $option, $locationId)->count(),
            ],
            [
                $this->buildQuery(1, $time, $option, $locationId)->sum('amount'),
                $this->buildQuery(1, $time, $option, $locationId)->count(),
            ],
            [
                $this->buildQuery(2, $time, $option, $locationId)->sum('amount'),
                $this->buildQuery(2, $time, $option, $locationId)->count(),
            ]
        ];

        $newArray = array_map(function ($item) {
            return ['total_price' => $item[0], 'total_count' => $item[1]];
        }, $count);

        return [
            'title' => [
                'Tiền mặt',
                'Chuyển khoản',
                'Ghi nợ'
            ],
            'data' => [
                $this->buildQuery(0, $time, $option, $locationId)->sum('amount'),
                $this->buildQuery(1, $time, $option, $locationId)->sum('amount'),
                $this->buildQuery(2, $time, $option, $locationId)->sum('amount'),
            ],
            'count' => $newArray
        ];
    }

    public function paymentMethod(array $option = [], ?int $locationId = 0){
        switch ($option[0]){
            case 'today':
               return $this->returnData(Carbon::today(), $option, $locationId);
            case 'yesterday':
                return $this->returnData(Carbon::yesterday(), $option, $locationId);
            case 'sevenDays':
                return $this->returnData(Carbon::now()->subDays(7), $option, $locationId);
            case 'thirtyDays':
                return $this->returnData(Carbon::now()->subDays(30), $option, $locationId);
            case 'fromTo':
                return $this->returnData(null, $option, $locationId);
            default:
                return responseApi("Lỗi", false);
        }
    }
}
