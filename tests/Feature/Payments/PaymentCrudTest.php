<?php

namespace Tests\Feature\Payments;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_payment_pages(): void
    {
        $payment = Payment::factory()->create();

        $this->get(route('payments.index'))->assertRedirect(route('login'));
        $this->get(route('payments.create'))->assertRedirect(route('login'));
        $this->post(route('payments.store'), [])->assertRedirect(route('login'));
        $this->get(route('payments.show', $payment))->assertRedirect(route('login'));
        $this->get(route('payments.edit', $payment))->assertRedirect(route('login'));
        $this->put(route('payments.update', $payment), [])->assertRedirect(route('login'));
        $this->delete(route('payments.destroy', $payment))->assertRedirect(route('login'));
        $this->post(route('payments.mark-paid', $payment))->assertRedirect(route('login'));
        $this->post(route('payments.mark-failed', $payment))->assertRedirect(route('login'));
    }

    public function test_staff_can_search_filter_and_sort_payments(): void
    {
        $staff = User::factory()->staff()->create();
        $budi = Customer::factory()->create(['name' => 'Budi Santoso']);
        $ani = Customer::factory()->create(['name' => 'Ani Lestari']);
        $rentalA = Rental::factory()->create([
            'rental_code' => 'RNT-20261201-AAAA',
            'customer_id' => $budi->id,
            'total_amount' => 1000000,
        ]);
        $rentalB = Rental::factory()->create([
            'rental_code' => 'RNT-20261202-BBBB',
            'customer_id' => $ani->id,
            'total_amount' => 500000,
        ]);

        $cashPending = Payment::factory()->create([
            'payment_code' => 'PAY-20261201-1111',
            'rental_id' => $rentalA->id,
            'amount' => 750000,
            'method' => PaymentMethod::Cash,
            'status' => PaymentStatus::Pending,
        ]);
        $transferPaid = Payment::factory()->paid()->create([
            'payment_code' => 'PAY-20261202-2222',
            'rental_id' => $rentalB->id,
            'amount' => 250000,
            'method' => PaymentMethod::Transfer,
        ]);

        $this->actingAs($staff)
            ->get(route('payments.index', ['search' => 'Budi']))
            ->assertOk()
            ->assertSee($cashPending->payment_code)
            ->assertDontSee($transferPaid->payment_code);

        $this->actingAs($staff)
            ->get(route('payments.index', ['search' => 'PAY-20261202-2222']))
            ->assertOk()
            ->assertSee($transferPaid->payment_code)
            ->assertDontSee($cashPending->payment_code);

        $this->actingAs($staff)
            ->get(route('payments.index', ['status' => PaymentStatus::Paid->value]))
            ->assertOk()
            ->assertSee($transferPaid->payment_code)
            ->assertDontSee($cashPending->payment_code);

        $this->actingAs($staff)
            ->get(route('payments.index', ['method' => PaymentMethod::Cash->value]))
            ->assertOk()
            ->assertSee($cashPending->payment_code)
            ->assertDontSee($transferPaid->payment_code);

        $this->actingAs($staff)
            ->get(route('payments.index', ['sort' => 'amount_desc']))
            ->assertOk()
            ->assertSeeInOrder([$cashPending->payment_code, $transferPaid->payment_code]);
    }

    public function test_payment_index_is_paginated(): void
    {
        $staff = User::factory()->staff()->create();
        Payment::factory()->count(12)->create();

        $this->actingAs($staff)
            ->get(route('payments.index'))
            ->assertOk()
            ->assertViewHas('payments', fn ($payments): bool => $payments->count() === 10 && $payments->total() === 12);
    }

    public function test_staff_can_record_pending_and_paid_payments(): void
    {
        $staff = User::factory()->staff()->create();
        $rental = Rental::factory()->create([
            'total_amount' => 1000000,
            'late_fee' => 0,
        ]);

        $pendingResponse = $this->actingAs($staff)->post(route('payments.store'), [
            'rental_id' => $rental->id,
            'amount' => 400000,
            'method' => PaymentMethod::Cash->value,
            'status' => PaymentStatus::Pending->value,
            'notes' => 'DP sewa',
        ]);

        $pendingPayment = Payment::query()->where('rental_id', $rental->id)->firstOrFail();
        $pendingResponse->assertRedirect(route('payments.show', $pendingPayment));
        $this->assertStringStartsWith('PAY-', $pendingPayment->payment_code);
        $this->assertSame('400000.00', $pendingPayment->amount);
        $this->assertSame(PaymentStatus::Pending, $pendingPayment->status);
        $this->assertNull($pendingPayment->paid_at);

        $paidResponse = $this->actingAs($staff)->post(route('payments.store'), [
            'rental_id' => $rental->id,
            'amount' => 600000,
            'method' => PaymentMethod::Transfer->value,
            'status' => PaymentStatus::Paid->value,
        ]);

        $paidPayment = Payment::query()
            ->where('rental_id', $rental->id)
            ->where('status', PaymentStatus::Paid->value)
            ->firstOrFail();
        $paidResponse->assertRedirect(route('payments.show', $paidPayment));
        $this->assertNotNull($paidPayment->paid_at);
    }

    public function test_payment_store_validates_required_fields_and_positive_amount(): void
    {
        $staff = User::factory()->staff()->create();
        $rental = Rental::factory()->create(['total_amount' => 500000]);

        $this->actingAs($staff)
            ->post(route('payments.store'), [])
            ->assertSessionHasErrors(['rental_id', 'amount', 'method', 'status']);

        $this->actingAs($staff)
            ->post(route('payments.store'), [
                'rental_id' => $rental->id,
                'amount' => 0,
                'method' => PaymentMethod::Cash->value,
                'status' => PaymentStatus::Pending->value,
            ])
            ->assertSessionHasErrors(['amount']);

        $this->assertDatabaseCount('payments', 0);
    }

    public function test_payment_rejects_cancelled_rental_and_overpayment(): void
    {
        $staff = User::factory()->staff()->create();
        $cancelledRental = Rental::factory()->cancelled()->create([
            'total_amount' => 500000,
        ]);

        $this->actingAs($staff)
            ->post(route('payments.store'), [
                'rental_id' => $cancelledRental->id,
                'amount' => 100000,
                'method' => PaymentMethod::Cash->value,
                'status' => PaymentStatus::Pending->value,
            ])
            ->assertSessionHas('error');

        $completedRental = Rental::factory()->completed()->create([
            'total_amount' => 500000,
            'late_fee' => 150000,
        ]);
        Payment::factory()->paid()->create([
            'rental_id' => $completedRental->id,
            'amount' => 500000,
        ]);

        $this->actingAs($staff)
            ->post(route('payments.store'), [
                'rental_id' => $completedRental->id,
                'amount' => 200000,
                'method' => PaymentMethod::Transfer->value,
                'status' => PaymentStatus::Paid->value,
            ])
            ->assertSessionHas('error');

        $this->actingAs($staff)
            ->post(route('payments.store'), [
                'rental_id' => $completedRental->id,
                'amount' => 150000,
                'method' => PaymentMethod::Transfer->value,
                'status' => PaymentStatus::Paid->value,
            ])
            ->assertSessionHas('success');

        $this->assertSame(650000.0, $completedRental->fresh()->paidAmount());
    }

    public function test_staff_can_update_and_delete_pending_payment(): void
    {
        $staff = User::factory()->staff()->create();
        $rental = Rental::factory()->create(['total_amount' => 800000, 'late_fee' => 0]);
        $payment = Payment::factory()->create([
            'rental_id' => $rental->id,
            'amount' => 300000,
            'method' => PaymentMethod::Cash,
            'status' => PaymentStatus::Pending,
        ]);

        $this->actingAs($staff)
            ->put(route('payments.update', $payment), [
                'amount' => 500000,
                'method' => PaymentMethod::Transfer->value,
                'notes' => 'Diubah ke transfer',
            ])
            ->assertRedirect(route('payments.show', $payment))
            ->assertSessionHas('success');

        $payment->refresh();
        $this->assertSame('500000.00', $payment->amount);
        $this->assertSame(PaymentMethod::Transfer, $payment->method);
        $this->assertSame('Diubah ke transfer', $payment->notes);

        $this->actingAs($staff)
            ->delete(route('payments.destroy', $payment))
            ->assertRedirect(route('payments.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }

    public function test_update_pending_payment_rejects_overpayment(): void
    {
        $staff = User::factory()->staff()->create();
        $rental = Rental::factory()->create(['total_amount' => 500000, 'late_fee' => 0]);
        Payment::factory()->paid()->create([
            'rental_id' => $rental->id,
            'amount' => 300000,
        ]);
        $pending = Payment::factory()->create([
            'rental_id' => $rental->id,
            'amount' => 100000,
            'status' => PaymentStatus::Pending,
        ]);

        $this->actingAs($staff)
            ->put(route('payments.update', $pending), [
                'amount' => 250000,
                'method' => PaymentMethod::Cash->value,
            ])
            ->assertSessionHas('error');

        $this->assertSame('100000.00', $pending->fresh()->amount);
    }

    public function test_staff_can_mark_pending_payment_as_paid_or_failed(): void
    {
        $staff = User::factory()->staff()->create();
        $rental = Rental::factory()->create(['total_amount' => 600000, 'late_fee' => 0]);

        $first = Payment::factory()->create([
            'rental_id' => $rental->id,
            'amount' => 400000,
            'status' => PaymentStatus::Pending,
        ]);
        $second = Payment::factory()->create([
            'rental_id' => $rental->id,
            'amount' => 400000,
            'status' => PaymentStatus::Pending,
        ]);
        $third = Payment::factory()->create([
            'rental_id' => $rental->id,
            'amount' => 200000,
            'status' => PaymentStatus::Pending,
        ]);

        $this->actingAs($staff)
            ->post(route('payments.mark-paid', $first))
            ->assertRedirect(route('payments.show', $first))
            ->assertSessionHas('success');

        $this->assertSame(PaymentStatus::Paid, $first->fresh()->status);
        $this->assertNotNull($first->fresh()->paid_at);

        $this->actingAs($staff)
            ->post(route('payments.mark-paid', $second))
            ->assertSessionHas('error');
        $this->assertSame(PaymentStatus::Pending, $second->fresh()->status);

        $this->actingAs($staff)
            ->post(route('payments.mark-failed', $third))
            ->assertRedirect(route('payments.show', $third))
            ->assertSessionHas('success');
        $this->assertSame(PaymentStatus::Failed, $third->fresh()->status);
    }

    public function test_processed_payments_are_locked_from_edit_delete_and_status_changes(): void
    {
        $staff = User::factory()->staff()->create();
        $paid = Payment::factory()->paid()->create(['amount' => 200000]);
        $failed = Payment::factory()->failed()->create(['amount' => 200000]);
        $cancelled = Payment::factory()->create([
            'amount' => 200000,
            'status' => PaymentStatus::Cancelled,
        ]);

        foreach ([$paid, $failed, $cancelled] as $payment) {
            $this->actingAs($staff)
                ->get(route('payments.edit', $payment))
                ->assertRedirect(route('payments.show', $payment))
                ->assertSessionHas('error');

            $this->actingAs($staff)
                ->put(route('payments.update', $payment), [
                    'amount' => 100000,
                    'method' => PaymentMethod::Cash->value,
                ])
                ->assertRedirect(route('payments.show', $payment))
                ->assertSessionHas('error');

            $this->actingAs($staff)
                ->delete(route('payments.destroy', $payment))
                ->assertRedirect(route('payments.show', $payment))
                ->assertSessionHas('error');

            $this->actingAs($staff)
                ->post(route('payments.mark-paid', $payment))
                ->assertSessionHas('error');

            $this->actingAs($staff)
                ->post(route('payments.mark-failed', $payment))
                ->assertSessionHas('error');

            $this->assertDatabaseHas('payments', [
                'id' => $payment->id,
                'amount' => '200000.00',
            ]);
        }
    }
}
