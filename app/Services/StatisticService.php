<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SubscriptionOrder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticService
{
    protected $cacheDuration;

    public function __construct()
    {
        $this->cacheDuration = now()->addHour(1);
    }
    public function getTotalByDay($date)
    {
        $cacheKey = "orders-total-day-" . $date->format('Y-m-d');
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($date) {
            return Order::whereDate('created_at', $date)
                ->sum('total');
        });
    }

    public function getDailyTotalsForMonth($year, $month)
    {
        $cacheKey = "daily-totals-{$year}-{$month}";
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($year, $month) {
            return Order::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy(DB::raw('Date(created_at)'))
                ->select(DB::raw('Date(created_at) as date'), DB::raw('SUM(total) as total'))
                ->orderBy('date', 'asc')
                ->get()
                ->pluck('total', 'date');
        });
    }

    public function getMonthlyTotalsForYear($year)
    {
        $cacheKey = "monthly-totals-{$year}";
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($year) {
            return Order::whereYear('created_at', $year)
                ->groupBy(DB::raw('Month(created_at)'))
                ->select(DB::raw('Month(created_at) as month'), DB::raw('SUM(total) as total'))
                ->orderBy('month', 'asc')
                ->get()
                ->pluck('total', 'month');
        });
    }

    public function getCurrentWeekStats()
    {
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
        $cacheKey = "current-week-stats-{$startOfWeek}-to-{$endOfWeek}";
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($startOfWeek, $endOfWeek) {
            return Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as daily_total')
                )
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->pluck('daily_total', 'date');
        });
    }

}
