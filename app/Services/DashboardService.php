<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\VehicleStatus;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * @return array<string, int|float>
     */
    public function summary(): array
    {
        return [
            'customers' => Customer::query()->count(),
            'vehicles_total' => Vehicle::query()->count(),
            'vehicles_available' => Vehicle::query()->available()->count(),
            'vehicles_rented' => Vehicle::query()->where('status', VehicleStatus::Rented->value)->count(),
            'active_rentals' => Rental::query()->active()->count(),
            'monthly_revenue' => $this->currentMonthRevenue(),
        ];
    }

    /**
     * @return array{labels: array<int, string>, totals: array<int, float>}
     */
    public function monthlyRevenue(int $months = 6): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);

        $payments = Payment::query()
            ->where('status', PaymentStatus::Paid->value)
            ->where('paid_at', '>=', $start)
            ->get(['paid_at', 'amount']);

        $labels = [];
        $totals = [];

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);

            $labels[] = $month->locale('id')->isoFormat('MMM Y');
            $totals[] = (float) $payments
                ->filter(fn (Payment $payment): bool => $payment->paid_at?->format('Y-m') === $month->format('Y-m'))
                ->sum('amount');
        }

        return ['labels' => $labels, 'totals' => $totals];
    }

    /**
     * @return array{labels: array<int, string>, totals: array<int, int>, colors: array<int, string>}
     */
    public function vehicleStatusComposition(): array
    {
        $counts = Vehicle::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $colors = [
            VehicleStatus::Available->value => '#10b981',
            VehicleStatus::Rented->value => '#3b82f6',
            VehicleStatus::Maintenance->value => '#f59e0b',
        ];

        $labels = [];
        $totals = [];
        $palette = [];

        foreach (VehicleStatus::cases() as $status) {
            $labels[] = $status->label();
            $totals[] = (int) ($counts[$status->value] ?? 0);
            $palette[] = $colors[$status->value];
        }

        return ['labels' => $labels, 'totals' => $totals, 'colors' => $palette];
    }

    /**
     * @return Collection<int, Rental>
     */
    public function latestRentals(int $limit = 5): Collection
    {
        return Rental::query()
            ->with(['customer', 'vehicle'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    protected function currentMonthRevenue(): float
    {
        return (float) Payment::query()
            ->where('status', PaymentStatus::Paid->value)
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
    }
}
