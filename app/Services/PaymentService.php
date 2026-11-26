<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Models\Payment;
use App\Models\Rental;
use App\Support\DocumentCodeGenerator;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function record(Rental $rental, array $data): Payment
    {
        if ($rental->status === RentalStatus::Cancelled) {
            throw new InvalidArgumentException('Pembayaran tidak dapat dicatat untuk rental yang dibatalkan.');
        }

        $amount = round((float) ($data['amount'] ?? 0), 2);

        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal pembayaran harus lebih dari 0.');
        }

        $status = $data['status'] ?? PaymentStatus::Pending;
        $resolvedStatus = $status instanceof PaymentStatus
            ? $status
            : PaymentStatus::from((string) $status);

        if (! in_array($resolvedStatus, [PaymentStatus::Pending, PaymentStatus::Paid], true)) {
            throw new InvalidArgumentException('Status pembayaran awal hanya boleh Menunggu atau Lunas.');
        }

        $method = $data['method'];
        $resolvedMethod = $method instanceof PaymentMethod
            ? $method
            : PaymentMethod::from((string) $method);

        return DB::transaction(function () use ($rental, $amount, $resolvedMethod, $resolvedStatus, $data): Payment {
            $freshRental = Rental::query()->lockForUpdate()->findOrFail($rental->id);

            if ($freshRental->status === RentalStatus::Cancelled) {
                throw new InvalidArgumentException('Pembayaran tidak dapat dicatat untuk rental yang dibatalkan.');
            }

            $this->ensureNotOverpaid($freshRental, $amount);

            $paidAt = null;
            if ($resolvedStatus === PaymentStatus::Paid) {
                $paidAt = ! empty($data['paid_at']) ? Carbon::parse($data['paid_at']) : now();
            }

            return Payment::create([
                'payment_code' => DocumentCodeGenerator::paymentCode(),
                'rental_id' => $freshRental->id,
                'amount' => $amount,
                'method' => $resolvedMethod,
                'status' => $resolvedStatus,
                'paid_at' => $paidAt,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updatePending(Payment $payment, array $data): Payment
    {
        $this->ensurePending($payment);

        $amount = round((float) ($data['amount'] ?? $payment->amount), 2);

        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal pembayaran harus lebih dari 0.');
        }

        $method = $data['method'] ?? $payment->method;
        $resolvedMethod = $method instanceof PaymentMethod
            ? $method
            : PaymentMethod::from((string) $method);

        return DB::transaction(function () use ($payment, $amount, $resolvedMethod, $data): Payment {
            $freshPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            $this->ensurePending($freshPayment);

            $freshRental = Rental::query()->lockForUpdate()->findOrFail($freshPayment->rental_id);

            if ($freshRental->status === RentalStatus::Cancelled) {
                throw new InvalidArgumentException('Pembayaran untuk rental yang dibatalkan tidak dapat diubah.');
            }

            $this->ensureNotOverpaid($freshRental, $amount);

            $freshPayment->update([
                'amount' => $amount,
                'method' => $resolvedMethod,
                'notes' => array_key_exists('notes', $data) ? $data['notes'] : $freshPayment->notes,
            ]);

            return $freshPayment->refresh();
        });
    }

    public function markPaid(Payment $payment, CarbonInterface|string|null $paidAt = null): Payment
    {
        return DB::transaction(function () use ($payment, $paidAt): Payment {
            $freshPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            $this->ensurePending($freshPayment);

            $freshRental = Rental::query()->lockForUpdate()->findOrFail($freshPayment->rental_id);

            if ($freshRental->status === RentalStatus::Cancelled) {
                throw new InvalidArgumentException('Pembayaran untuk rental yang dibatalkan tidak dapat dilunasi.');
            }

            $this->ensureNotOverpaid($freshRental, (float) $freshPayment->amount);

            $freshPayment->update([
                'status' => PaymentStatus::Paid,
                'paid_at' => $paidAt !== null ? Carbon::parse($paidAt) : now(),
            ]);

            return $freshPayment->refresh();
        });
    }

    public function markFailed(Payment $payment, ?string $notes = null): Payment
    {
        return DB::transaction(function () use ($payment, $notes): Payment {
            $freshPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            $this->ensurePending($freshPayment);

            $freshPayment->update([
                'status' => PaymentStatus::Failed,
                'paid_at' => null,
                'notes' => $notes ?? $freshPayment->notes,
            ]);

            return $freshPayment->refresh();
        });
    }

    public function deletePending(Payment $payment): void
    {
        DB::transaction(function () use ($payment): void {
            $freshPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            $this->ensurePending($freshPayment);
            $freshPayment->delete();
        });
    }

    public function remainingBill(Rental $rental): float
    {
        return max(0.0, round($rental->totalBill() - $rental->paidAmount(), 2));
    }

    private function ensurePending(Payment $payment): void
    {
        if ($payment->status !== PaymentStatus::Pending) {
            throw new InvalidArgumentException('Pembayaran yang sudah diproses tidak dapat diubah atau dihapus.');
        }
    }

    private function ensureNotOverpaid(Rental $rental, float $amount): void
    {
        $remaining = round($rental->totalBill() - $rental->paidAmount(), 2);

        if ($remaining <= 0) {
            throw new InvalidArgumentException('Tagihan rental sudah lunas.');
        }

        if (round($amount, 2) > $remaining) {
            $formattedRemaining = number_format($remaining, 0, ',', '.');

            throw new InvalidArgumentException("Nominal pembayaran melebihi sisa tagihan (Rp {$formattedRemaining}).");
        }
    }
}
