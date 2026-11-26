@extends('layouts.app')

@section('title', 'Tambah Kendaraan')

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('vehicles.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                ← Kembali ke Kendaraan
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Tambah Kendaraan</h1>
            <p class="text-sm text-slate-500">Daftarkan kendaraan baru beserta tarif sewa dan foto.</p>
        </div>

        <form method="POST" action="{{ route('vehicles.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @include('vehicles._form')

            <div class="flex flex-wrap items-center justify-end gap-3">
                <x-button :href="route('vehicles.index')" variant="secondary">Batal</x-button>
                <x-button type="submit">Simpan Kendaraan</x-button>
            </div>
        </form>
    </div>
@endsection
