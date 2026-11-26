@extends('layouts.app')

@section('title', 'Edit Rental '.$rental->rental_code)

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('rentals.show', $rental) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                ← Kembali ke Detail Rental
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Edit Rental {{ $rental->rental_code }}</h1>
            <p class="text-sm text-slate-500">Perbarui tanggal sewa atau catatan selama transaksi masih berstatus aktif.</p>
        </div>

        <form
            method="POST"
            action="{{ route('rentals.update', $rental) }}"
            data-rental-estimator
            data-vehicle-type="{{ $rental->vehicle?->type->value }}"
            data-daily-rate="{{ (float) $rental->daily_rate }}"
            data-car-insurance-rate="{{ $pricingConfig['car_insurance_rate'] }}"
            data-motorcycle-discount-rate="{{ $pricingConfig['motorcycle_weekly_discount_rate'] }}"
            data-motorcycle-min-days="{{ $pricingConfig['motorcycle_weekly_min_days'] }}"
            class="grid grid-cols-1 gap-6 lg:grid-cols-3"
        >
            @csrf
            @method('PUT')

            <div class="space-y-6 lg:col-span-2">
                <x-card title="Informasi Terkunci" subtitle="Pelanggan, kendaraan, dan snapshot tarif harian tidak diubah pada edit rental.">
                    <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-3">
                        <div>
                            <dt class="text-xs text-slate-500">Pelanggan</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">{{ $rental->customer?->name ?? 'Pelanggan dihapus' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Kendaraan</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">
                                @if ($rental->vehicle)
                                    {{ $rental->vehicle->brand }} {{ $rental->vehicle->model }} ({{ $rental->vehicle->plate_number }})
                                @else
                                    Kendaraan dihapus
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Snapshot Tarif Harian</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">Rp {{ number_format((float) $rental->daily_rate, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </x-card>

                <x-card title="Ubah Jadwal & Catatan" subtitle="Total biaya akan dihitung ulang secara otomatis.">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <x-input
                            name="start_date"
                            type="date"
                            label="Tanggal Mulai"
                            :value="$rental->start_date->toDateString()"
                            data-rental-start
                            required
                        />

                        <x-input
                            name="end_date"
                            type="date"
                            label="Tanggal Selesai"
                            :value="$rental->end_date->toDateString()"
                            data-rental-end
                            required
                        />

                        <div class="space-y-1.5 md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('notes') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                            >{{ old('notes', $rental->notes) }}</textarea>
                            @error('notes')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-card>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <x-button :href="route('rentals.show', $rental)" variant="secondary">Batal</x-button>
                    <x-button type="submit">Simpan Perubahan</x-button>
                </div>
            </div>

            <div>
                <x-card title="Estimasi Biaya Baru" subtitle="Menggunakan snapshot tarif harian">
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Tarif Snapshot</dt>
                            <dd class="font-medium text-slate-900" data-rental-rate>Rp {{ number_format((float) $rental->daily_rate, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Estimasi Durasi</dt>
                            <dd class="font-medium text-slate-900" data-rental-days>{{ $rental->duration_days }} hari</dd>
                        </div>
                        <div class="flex justify-between border-t border-slate-100 pt-3">
                            <dt class="font-semibold text-slate-900">Estimasi Total</dt>
                            <dd class="text-base font-bold text-indigo-600" data-rental-total>Rp {{ number_format((float) $rental->total_amount, 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <p class="mt-4 rounded-lg bg-slate-50 p-3 text-xs text-slate-600" data-rental-rule>
                        Perhitungan biaya mengikuti tipe kendaraan saat transaksi dibuat.
                    </p>
                </x-card>
            </div>
        </form>
    </div>
@endsection
