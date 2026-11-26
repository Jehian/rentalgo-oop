@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('customers.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                ← Kembali ke Pelanggan
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Tambah Pelanggan</h1>
            <p class="text-sm text-slate-500">Daftarkan pelanggan baru untuk kebutuhan transaksi rental.</p>
        </div>

        <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
            @csrf

            @include('customers._form')

            <div class="flex flex-wrap items-center justify-end gap-3">
                <x-button :href="route('customers.index')" variant="secondary">Batal</x-button>
                <x-button type="submit">Simpan Pelanggan</x-button>
            </div>
        </form>
    </div>
@endsection
