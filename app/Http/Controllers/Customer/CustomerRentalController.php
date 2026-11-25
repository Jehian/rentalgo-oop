<?php

namespace App\Http\Controllers\Customer;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Exceptions\UnsupportedVehicleTypeException;
use App\Exceptions\VehicleNotAvailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerBookingRequest;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\Vehicle;
use App\Services\RentalService;
use App\Support\DocumentCodeGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CustomerRentalController extends Controller
{
    public function __construct(
        private readonly RentalService $rentalService,
    ) {}

    public function index(Request $request): View
    {
        $customer = $this->resolveCustomer();
        $status = RentalStatus::tryFrom((string) $request->query('status', ''));

        $query = $customer->rentals()
            ->with(['vehicle', 'payments'])
            ->latest();

        if ($status !== null) {
            $query->where('status', $status->value);
        }

        return view('customer.rentals.index', [
            'rentals' => $query->paginate(10)->withQueryString(),
            'selectedStatus' => $status?->value ?? '',
            'rentalStatuses' => RentalStatus::cases(),
        ]);
    }

    public function store(CustomerBookingRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $customer = $this->resolveCustomer();
        $validated = $request->validated();

        try {
            $rental = DB::transaction(function () use ($customer, $vehicle, $validated): Rental {
                $rental = $this->rentalService->createRental(
                    $customer,
                    $vehicle,
                    $validated['start_date'],
                    $validated['end_date'],
                    null,
                    $validated['notes'] ?? null,
                );

                if (! empty($validated['payment_method'])) {
                    Payment::create([
                        'payment_code' => DocumentCodeGenerator::paymentCode(),
                        'rental_id' => $rental->id,
                        'amount' => $rental->total_amount,
                        'method' => PaymentMethod::from($validated['payment_method']),
                        'status' => PaymentStatus::Pending,
                        'notes' => 'Dibuat oleh pelanggan saat booking',
                    ]);
                }

                return $rental;
            });
        } catch (VehicleNotAvailableException|UnsupportedVehicleTypeException|InvalidArgumentException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('customer.rentals.show', $rental)
            ->with('success', "Booking berhasil dibuat dengan kode {$rental->rental_code}.");
    }

    public function show(Rental $rental): View
    {
        $customer = $this->resolveCustomer();
        abort_if($rental->customer_id !== $customer->id, 403, 'Anda tidak memiliki akses ke pesanan ini.');

        $rental->load(['vehicle', 'payments', 'customer']);

        return view('customer.rentals.show', [
            'rental' => $rental,
        ]);
    }

    public function cancel(Rental $rental): RedirectResponse
    {
        $customer = $this->resolveCustomer();
        abort_if($rental->customer_id !== $customer->id, 403, 'Anda tidak memiliki akses ke pesanan ini.');

        try {
            $this->rentalService->cancelRental($rental);
        } catch (InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('customer.rentals.show', $rental)
            ->with('success', 'Pesanan rental berhasil dibatalkan.');
    }

    private function resolveCustomer(): Customer
    {
        $customer = Auth::guard('customer')->user();

        abort_if(! $customer instanceof Customer, 403, 'Profil pelanggan tidak ditemukan.');

        return $customer;
    }
}
