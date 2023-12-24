<?php

namespace App\Http\Controllers;

use App\Models\BusinessField;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Pricing;
use App\Models\Order;
use Carbon\Carbon;
use App\Services\StatisticService;

class StatisticController extends Controller
{
    protected $statisticService;

    public function __construct(StatisticService $statisticService)
    {
        $this->statisticService = $statisticService;
    }

    public function index()
    {
        $users = User::role('customer')->count();
        $tenants = Tenant::count();
        $pricings = Pricing::count();
        $orderMonth = Order::query()->statisticMonth();
        $orderWeek = Order::query()->statisticWeek();
        $orderToDay = Order::query()->statisticDay();
        $date = Carbon::today();
        $month = $date->month;
        $reponse = [
            'user' => $users,
            'tenant' => $tenants,
            'pricing' => $pricings,
            'month' => $month,
            'orderDay' => $orderToDay,
            'orderWeek' => $orderWeek,
            'orderMonth' => $orderMonth
        ];
        return view('admin.dashboard.index', compact('reponse'));
    }

    public function getStatistic()
    {
        $date = Carbon::today();
        $month = $date->month;
        $year = $date->year;
        $plan= Pricing::query()->statistic();
        $categories= BusinessField::query()->statistic();
        $totalThisWeek = $this->statisticService->getCurrentWeekStats();
        $totalThisMonth = $this->statisticService->getDailyTotalsForMonth($year, $month);
        $totalThisYear = $this->statisticService->getMonthlyTotalsForYear($year);
        $totalThisYear = [
            'month' => $totalThisYear->keys(),
            'total' => $totalThisYear->values()
        ];
        $totalThisMonth = [
            'day' => $totalThisMonth->keys(),
            'total' => $totalThisMonth->values()
        ];
        $totalThisWeek = [
            'day' => $totalThisWeek->keys(),
            'total' => $totalThisWeek->values()
        ];
        $plan = [
            'name' => $plan->keys(),
            'total' => $plan->values()
        ];
        $categories = [
            'name' => $categories->keys(),
            'total' => $categories->values()
        ];
        $reponse = [
            'plan' => $plan,
            'categories' => $categories,
            'ordersByYear' => $totalThisYear,
            'ordersByWeek' => $totalThisWeek,
            'ordersByMonth' => $totalThisMonth
        ];
        return $reponse;
    }
}
