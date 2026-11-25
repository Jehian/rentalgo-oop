@extends('layouts.guest')

@section('title', 'Masuk Pelanggan')

@section('content')
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-slate-900">Masuk Pelanggan</h1>
        <p class="mt-1 text-sm text-slate-500">Masuk untuk menyewa kendaraan dan melihat riwayat pesanan Anda.</p>
    </div>

    @if ($errors->any())
        <x-alert type="error" message="Email atau password yang Anda masukkan tidak valid." class="mb-5" />
    @endif

    <form method="POST" action="{{ route('customer.login.store') }}" class="space-y-4">
        @csrf

        <x-input
            name="email"
            label="Email"
            type="email"
            placeholder="nama@email.com"
            autocomplete="username"
            required
            autofocus
        />

        <x-input
            name="password"
            label="Password"
            type="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required
        />

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input
                type="checkbox"
                name="remember"
                value="1"
                @checked(old('remember'))
                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
            >
            Ingat saya
        </label>

        <x-button type="submit" class="w-full">Masuk</x-button>
    </form>

    <div class="mt-5 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-4 text-sm text-slate-600">
        <a href="{{ route('home') }}" class="hover:text-slate-900">← Kembali ke Katalog</a>
        <a href="{{ route('customer.register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Daftar Akun</a>
    </div>
@endsection
