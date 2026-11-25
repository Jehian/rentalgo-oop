@extends('layouts.guest')

@section('title', 'Daftar Pelanggan')

@section('content')
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-slate-900">Daftar Akun Pelanggan</h1>
        <p class="mt-1 text-sm text-slate-500">Buat akun pelanggan untuk menyewa kendaraan di RentalGo.</p>
    </div>

    @if ($errors->any())
        <x-alert type="error" message="Periksa kembali data pendaftaran Anda." class="mb-5" />
    @endif

    <form method="POST" action="{{ route('customer.register.store') }}" class="space-y-4">
        @csrf

        <x-input
            name="name"
            label="Nama Lengkap"
            placeholder="Budi Santoso"
            autocomplete="name"
            required
            autofocus
        />

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-input
                name="email"
                label="Email"
                type="email"
                placeholder="budi@email.com"
                autocomplete="email"
                required
            />

            <x-input
                name="phone"
                label="Nomor Telepon"
                placeholder="081234567890"
                autocomplete="tel"
                required
            />
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-input
                name="identity_number"
                label="NIK / Nomor KTP"
                placeholder="3271000000000001"
                required
            />

            <x-input
                name="license_number"
                label="Nomor SIM"
                placeholder="SIM-1234567890"
                required
            />
        </div>

        <div class="space-y-1.5">
            <label for="address" class="block text-sm font-medium text-slate-700">Alamat (Opsional)</label>
            <textarea
                id="address"
                name="address"
                rows="2"
                placeholder="Alamat lengkap"
                class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-0 {{ $errors->has('address') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
            >{{ old('address') }}</textarea>
            @error('address')
                <p class="text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-input
                name="password"
                label="Password"
                type="password"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
                required
            />

            <x-input
                name="password_confirmation"
                label="Konfirmasi Password"
                type="password"
                placeholder="Ulangi password"
                autocomplete="new-password"
                required
            />
        </div>

        <x-button type="submit" class="w-full">Daftar Sekarang</x-button>
    </form>

    <div class="mt-5 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-4 text-sm text-slate-600">
        <a href="{{ route('home') }}" class="hover:text-slate-900">← Kembali ke Katalog</a>
        <a href="{{ route('customer.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Sudah punya akun? Masuk</a>
    </div>
@endsection
