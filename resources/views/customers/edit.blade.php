@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('customers.show', $customer) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                ← Kembali ke Detail Pelanggan
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Edit Pelanggan</h1>
            <p class="text-sm text-slate-500">Perbarui data identitas dan kontak {{ $customer->name }}.</p>
        </div>

        <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
            @csrf
            @method('PUT')

            @include('customers._form', ['customer' => $customer])

            <div class="flex flex-wrap items-center justify-end gap-3">
                <x-button :href="route('customers.show', $customer)" variant="secondary">Batal</x-button>
                <x-button type="submit">Simpan Perubahan</x-button>
            </div>
        </form>
    </div>
@endsection
