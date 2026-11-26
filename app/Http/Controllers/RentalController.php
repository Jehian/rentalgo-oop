<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Exceptions\UnsupportedVehicleTypeException;
use App\Exceptions\VehicleNotAvailableException;
use App\Http\Requests\ReturnRentalRequest;
use App\Http\Requests\StoreRentalRequest;
use App\Http\Requests\UpdateRentalRequest;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Vehicle;
use App\Services\RentalService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Throwable;

class RentalController extends Controller
{
    public function __construct(
        private readonly RentalService $rentalService,
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = RentalStatus::tryFrom((string) $request->query('status', ''));
        $dateFrom = $this->parseDateFilter((string) $request->query('date_from', ''));
        $dateTo = $this->parseDateFilter((string) $request->query('date_to', ''));
        $sort = (string) $request->query('sort', 'newest');

        $query = Rental::query()
            ->with([
                'customer' => fn ($builder) => $builder->withTrashed(),
                'vehicle' => fn ($builder) => $builder->withTrashed(),
                'user',
            ])
            ->withSum([
                'payments as paid_amount_sum' => fn ($builder) => $builder->where('status', PaymentStatus::Paid->value),
            ], 'amount');

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('rental_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search): void {
                        $customerQuery->withTrashed()
                            ->where(function ($sub) use ($search): void {
                                $sub->where('name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search): void {
                        $vehicleQuery->withTrashed()
                            ->where(function ($sub) use ($search): void {
                                $sub->where('plate_number', 'like', "%{$search}%")
                                    ->orWhere('brand', 'like', "%{$search}%")
                                    ->orWhere('model', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($status !== null) {
            $query->where('status', $status->value);
        }

        if ($dateFrom !== null) {
            $query->whereDate('start_date', '>=', $dateFrom);
        }

        if ($dateTo !== null) {
            $query->whereDate('start_date', '<=', $dateTo);
        }

        match ($sort) {
            'oldest' => $query->oldest(),
            'start_asc' => $query->orderBy('start_date'),
            'start_desc' => $query->orderByDesc('start_date'),
            'total_desc' => $query->orderByDesc('total_amount'),
            'total_asc' => $query->orderBy('total_amount'),
            default => $query->latest(),
        };

        return view('rentals.index', [
            'rentals' => $query->paginate(10)->withQueryString(),
            'filters' => [
                'search' => $search,
                'status' => $status?->value ?? '',
                'date_from' => $dateFrom ?? '',
                'date_to' => $dateTo ?? '',
                'sort' => $sort,
            ],
            'rentalStatuses' => RentalStatus::cases(),
        ]);
    }

    public function create(Request $request): View
    {
        $customers = Customer::query()
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'identity_number']);

        $vehicles = Vehicle::query()
            ->available()
            ->orderBy('brand')
            ->orderBy('model')
            ->get();

        return view('rentals.create', [
            'customers' => $customers,
            'vehicles' => $vehicles,
            'selectedCustomerId' => $request->query('customer_id'),
            'selectedVehicleId' => $request->query('vehicle_id'),
            'pricingConfig' => $this->pricingConfig(),
        ]);
    }

    public function store(StoreRentalRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $customer = Customer::query()->findOrFail($validated['customer_id']);
        $vehicle = Vehicle::query()->findOrFail($validated['vehicle_id']);

        try {
            $rental = $this->rentalService->createRental(
                $customer,
                $vehicle,
                $validated['start_date'],
                $validated['end_date'],
                $request->user(),
                $validated['notes'] ?? null,
            );
        } catch (VehicleNotAvailableException|UnsupportedVehicleTypeException|InvalidArgumentException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('rentals.show', $rental)
            ->with('success', "Transaksi rental {$rental->rental_code} berhasil dibuat.");
    }

    public function show(Rental $rental): View
    {
        $rental->load([
            'customer' => fn ($builder) => $builder->withTrashed(),
            'vehicle' => fn ($builder) => $builder->withTrashed(),
            'user',
            'payments' => fn ($builder) => $builder->latest(),
        ]);

        $totalBill = $rental->totalBill();
        $paidAmount = $rental->paidAmount();

        return view('rentals.show', [
            'rental' => $rental,
            'totalBill' => $totalBill,
            'paidAmount' => $paidAmount,
            'remainingBill' => max(0.0, round($totalBill - $paidAmount, 2)),
            'lateFeeRate' => (float) config('rental.late_fee_rate_per_day', 0.50),
        ]);
    }

    public function edit(Rental $rental): View|RedirectResponse
    {
        if ($rental->status !== RentalStatus::Active) {
            return redirect()
                ->route('rentals.show', $rental)
                ->with('error', 'Hanya rental aktif yang dapat diubah.');
        }

        $rental->load([
            'customer' => fn ($builder) => $builder->withTrashed(),
            'vehicle' => fn ($builder) => $builder->withTrashed(),
        ]);

        return view('rentals.edit', [
            'rental' => $rental,
            'pricingConfig' => $this->pricingConfig(),
        ]);
    }

    public function update(UpdateRentalRequest $request, Rental $rental): RedirectResponse
    {
        if ($rental->status !== RentalStatus::Active) {
            return redirect()
                ->route('rentals.show', $rental)
                ->with('error', 'Hanya rental aktif yang dapat diubah.');
        }

        $validated = $request->validated();

        try {
            $this->rentalService->updateRental($rental, [
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'notes' => $validated['notes'] ?? null,
            ]);
        } catch (VehicleNotAvailableException|UnsupportedVehicleTypeException|InvalidArgumentException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('rentals.show', $rental)
            ->with('success', 'Data rental berhasil diperbarui.');
    }

    public function returnVehicle(ReturnRentalRequest $request, Rental $rental): RedirectResponse
    {
        try {
            $completed = $this->rentalService->completeRental($rental, $request->validated('returned_at'));
        } catch (InvalidArgumentException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        $lateFee = (float) $completed->late_fee;
        $message = $lateFee > 0
            ? 'Pengembalian berhasil diproses dengan denda keterlambatan Rp '.number_format($lateFee, 0, ',', '.').'.'
            : 'Pengembalian kendaraan berhasil diproses tanpa denda.';

        return redirect()
            ->route('rentals.show', $completed)
            ->with('success', $message);
    }

    public function cancel(Rental $rental): RedirectResponse
    {
        try {
            $this->rentalService->cancelRental($rental);
        } catch (InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('rentals.show', $rental)
            ->with('success', 'Transaksi rental berhasil dibatalkan dan kendaraan kembali tersedia.');
    }

    /**
     * @return array<string, float|int>
     */
    private function pricingConfig(): array
    {
        return [
            'car_insurance_rate' => (float) config('rental.car_insurance_rate', 0.10),
            'motorcycle_weekly_discount_rate' => (float) config('rental.motorcycle_weekly_discount_rate', 0.10),
            'motorcycle_weekly_min_days' => (int) config('rental.motorcycle_weekly_min_days', 7),
        ];
    }

    private function parseDateFilter(string $value): ?string
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            return null;
        }

        try {
            return Carbon::parse($trimmed)->toDateString();
        } catch (Throwable) {
            return null;
        }
    }
}
