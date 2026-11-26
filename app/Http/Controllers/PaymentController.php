<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Rental;
use App\Services\PaymentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = PaymentStatus::tryFrom((string) $request->query('status', ''));
        $method = PaymentMethod::tryFrom((string) $request->query('method', ''));
        $sort = (string) $request->query('sort', 'newest');

        $query = Payment::query()
            ->with([
                'rental.customer' => fn ($builder) => $builder->withTrashed(),
                'rental.vehicle' => fn ($builder) => $builder->withTrashed(),
            ]);

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('payment_code', 'like', "%{$search}%")
                    ->orWhereHas('rental', function ($rentalQuery) use ($search): void {
                        $rentalQuery->where('rental_code', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($customerQuery) use ($search): void {
                                $customerQuery->withTrashed()
                                    ->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($status !== null) {
            $query->where('status', $status->value);
        }

        if ($method !== null) {
            $query->where('method', $method->value);
        }

        match ($sort) {
            'oldest' => $query->oldest(),
            'amount_desc' => $query->orderByDesc('amount'),
            'amount_asc' => $query->orderBy('amount'),
            default => $query->latest(),
        };

        return view('payments.index', [
            'payments' => $query->paginate(10)->withQueryString(),
            'filters' => [
                'search' => $search,
                'status' => $status?->value ?? '',
                'method' => $method?->value ?? '',
                'sort' => $sort,
            ],
            'paymentStatuses' => PaymentStatus::cases(),
            'paymentMethods' => PaymentMethod::cases(),
        ]);
    }

    public function create(Request $request): View
    {
        $selectedRentalId = $request->query('rental_id');

        $rentals = Rental::query()
            ->where('status', '!=', RentalStatus::Cancelled->value)
            ->with([
                'customer' => fn ($builder) => $builder->withTrashed(),
                'vehicle' => fn ($builder) => $builder->withTrashed(),
            ])
            ->withSum([
                'payments as paid_amount_sum' => fn ($builder) => $builder->where('status', PaymentStatus::Paid->value),
            ], 'amount')
            ->latest()
            ->get()
            ->map(function (Rental $rental): Rental {
                $paid = (float) ($rental->paid_amount_sum ?? 0);
                $rental->setAttribute('remaining_bill', max(0.0, round($rental->totalBill() - $paid, 2)));

                return $rental;
            })
            ->filter(fn (Rental $rental): bool => (float) $rental->getAttribute('remaining_bill') > 0 || (string) $rental->id === (string) $selectedRentalId)
            ->values();

        return view('payments.create', [
            'rentals' => $rentals,
            'selectedRentalId' => $selectedRentalId,
            'paymentMethods' => PaymentMethod::cases(),
            'paymentStatuses' => [PaymentStatus::Pending, PaymentStatus::Paid],
        ]);
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $rental = Rental::query()->findOrFail($validated['rental_id']);

        try {
            $payment = $this->paymentService->record($rental, $validated);
        } catch (InvalidArgumentException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', "Pembayaran {$payment->payment_code} berhasil dicatat.");
    }

    public function show(Payment $payment): View
    {
        $payment->load([
            'rental.customer' => fn ($builder) => $builder->withTrashed(),
            'rental.vehicle' => fn ($builder) => $builder->withTrashed(),
            'rental.payments',
        ]);

        return view('payments.show', [
            'payment' => $payment,
            'remainingBill' => $this->paymentService->remainingBill($payment->rental),
        ]);
    }

    public function edit(Payment $payment): View|RedirectResponse
    {
        if ($payment->status !== PaymentStatus::Pending) {
            return redirect()
                ->route('payments.show', $payment)
                ->with('error', 'Hanya pembayaran berstatus menunggu yang dapat diubah.');
        }

        $payment->load([
            'rental.customer' => fn ($builder) => $builder->withTrashed(),
            'rental.vehicle' => fn ($builder) => $builder->withTrashed(),
        ]);

        return view('payments.edit', [
            'payment' => $payment,
            'remainingBill' => $this->paymentService->remainingBill($payment->rental),
            'paymentMethods' => PaymentMethod::cases(),
        ]);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): RedirectResponse
    {
        if ($payment->status !== PaymentStatus::Pending) {
            return redirect()
                ->route('payments.show', $payment)
                ->with('error', 'Hanya pembayaran berstatus menunggu yang dapat diubah.');
        }

        try {
            $this->paymentService->updatePending($payment, $request->validated());
        } catch (InvalidArgumentException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        try {
            $this->paymentService->deletePending($payment);
        } catch (InvalidArgumentException $exception) {
            return redirect()
                ->route('payments.show', $payment)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    public function markPaid(Payment $payment): RedirectResponse
    {
        try {
            $this->paymentService->markPaid($payment);
        } catch (InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil ditandai lunas.');
    }

    public function markFailed(Payment $payment): RedirectResponse
    {
        try {
            $this->paymentService->markFailed($payment);
        } catch (InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil ditandai gagal.');
    }
}
