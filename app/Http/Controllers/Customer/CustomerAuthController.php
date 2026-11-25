<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerLoginRequest;
use App\Http\Requests\Customer\CustomerRegisterRequest;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('customer.auth.login');
    }

    public function login(CustomerLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function showRegister(): View
    {
        return view('customer.auth.register');
    }

    public function register(CustomerRegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'],
            'identity_number' => $validated['identity_number'],
            'license_number' => $validated['license_number'],
            'address' => $validated['address'] ?? null,
        ]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('success', 'Pendaftaran berhasil. Selamat datang di RentalGo!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda berhasil keluar.');
    }
}
