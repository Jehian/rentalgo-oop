@extends('layouts.guest')

@section('title', 'Akses Ditolak')

@section('content')
    <div class="text-center">
        <p class="text-sm font-semibold text-indigo-600">403</p>
        <h1 class="mt-2 text-xl font-semibold text-slate-900">Akses ditolak</h1>
        <p class="mt-2 text-sm text-slate-500">
            Halaman ini hanya dapat diakses oleh Admin RentalGo. Silakan kembali ke dashboard atau masuk dengan akun Admin.
        </p>

        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button href="{{ route('dashboard') }}">Kembali ke Dashboard</x-button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button type="submit" variant="secondary" class="w-full">Keluar</x-button>
            </form>
        </div>
    </div>
@endsection
