@extends('layouts.app')

@section('title', 'Edit Kendaraan')

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('vehicles.show', $vehicle) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                ← Kembali ke Detail Kendaraan
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Edit Kendaraan</h1>
            <p class="text-sm text-slate-500">Perbarui data {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }}).</p>
        </div>

        <form method="POST" action="{{ route('vehicles.update', $vehicle) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            @include('vehicles._form', ['vehicle' => $vehicle])

            <div class="flex flex-wrap items-center justify-end gap-3">
                <x-button :href="route('vehicles.show', $vehicle)" variant="secondary">Batal</x-button>
                <x-button type="submit">Simpan Perubahan</x-button>
            </div>
        </form>
    </div>
@endsection
