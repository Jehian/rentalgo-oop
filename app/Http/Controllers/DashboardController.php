<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(DashboardService $dashboardService): View
    {
        return view('dashboard.index', [
            'summary' => $dashboardService->summary(),
            'revenueChart' => $dashboardService->monthlyRevenue(),
            'vehicleChart' => $dashboardService->vehicleStatusComposition(),
            'latestRentals' => $dashboardService->latestRentals(),
        ]);
    }
}
