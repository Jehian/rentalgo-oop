<?php

namespace App\Http\Controllers;

use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $type = VehicleType::tryFrom((string) $request->query('type', ''));
        $status = VehicleStatus::tryFrom((string) $request->query('status', ''));
        $sort = (string) $request->query('sort', 'newest');

        $query = Vehicle::query();

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        if ($type !== null) {
            $query->ofType($type);
        }

        if ($status !== null) {
            $query->where('status', $status->value);
        }

        match ($sort) {
            'rate_asc' => $query->orderBy('daily_rate'),
            'rate_desc' => $query->orderByDesc('daily_rate'),
            default => $query->latest(),
        };

        return view('portal.index', [
            'vehicles' => $query->paginate(9)->withQueryString(),
            'filters' => [
                'search' => $search,
                'type' => $type?->value ?? '',
                'status' => $status?->value ?? '',
                'sort' => $sort,
            ],
            'stats' => [
                'total' => Vehicle::query()->count(),
                'available' => Vehicle::query()->available()->count(),
            ],
            'vehicleTypes' => VehicleType::cases(),
            'vehicleStatuses' => VehicleStatus::cases(),
        ]);
    }

    public function show(Vehicle $vehicle): View
    {
        $activeRentals = $vehicle->rentals()
            ->active()
            ->orderBy('start_date')
            ->get(['start_date', 'end_date']);

        return view('portal.vehicles.show', [
            'vehicle' => $vehicle,
            'activeRentals' => $activeRentals,
            'pricingConfig' => [
                'car_insurance_rate' => (float) config('rental.car_insurance_rate', 0.10),
                'motorcycle_weekly_discount_rate' => (float) config('rental.motorcycle_weekly_discount_rate', 0.10),
                'motorcycle_weekly_min_days' => (int) config('rental.motorcycle_weekly_min_days', 7),
            ],
        ]);
    }
}
