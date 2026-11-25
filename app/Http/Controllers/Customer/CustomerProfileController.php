<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateCustomerProfileRequest;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function edit(): View
    {
        $customer = $this->resolveCustomer();

        return view('customer.profile.edit', [
            'customer' => $customer,
        ]);
    }

    public function update(UpdateCustomerProfileRequest $request): RedirectResponse
    {
        $customer = $this->resolveCustomer();
        $customer->update($request->validated());

        return redirect()
            ->route('customer.profile.edit')
            ->with('success', 'Profil pelanggan berhasil diperbarui.');
    }

    private function resolveCustomer(): Customer
    {
        $customer = Auth::guard('customer')->user();

        abort_if(! $customer instanceof Customer, 403, 'Profil pelanggan tidak ditemukan.');

        return $customer;
    }
}
