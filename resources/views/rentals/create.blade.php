@extends('layouts.app')

@section('title', 'Buat Rental')

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('rentals.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                ← Kembali ke Transaksi Rental
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Buat Transaksi Rental</h1>
            <p class="text-sm text-slate-500">Pilih pelanggan, kendaraan tersedia, dan rentang tanggal sewa.</p>
        </div>

        <form
            method="POST"
            action="{{ route('rentals.store') }}"
            data-rental-estimator
            data-car-insurance-rate="{{ $pricingConfig['car_insurance_rate'] }}"
            data-motorcycle-discount-rate="{{ $pricingConfig['motorcycle_weekly_discount_rate'] }}"
            data-motorcycle-min-days="{{ $pricingConfig['motorcycle_weekly_min_days'] }}"
            class="grid grid-cols-1 gap-6 lg:grid-cols-3"
        >
            @csrf

            <div class="space-y-6 lg:col-span-2">
                <x-card title="Detail Sewa" subtitle="Ketersediaan kendaraan dan total biaya akan dihitung otomatis oleh RentalService.">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="space-y-1.5 md:col-span-2">
                            <label for="customer_id" class="block text-sm font-medium text-slate-700">Pelanggan</label>
                            <select
                                id="customer_id"
                                name="customer_id"
                                required
                                class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 {{ $errors->has('customer_id') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                            >
                                <option value="">Pilih pelanggan</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected((string) old('customer_id', $selectedCustomerId) === (string) $customer->id)>
                                        {{ $customer->name }} — {{ $customer->phone }} (NIK: {{ $customer->identity_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label for="vehicle_id" class="block text-sm font-medium text-slate-700">Kendaraan Tersedia</label>
                            <select
                                id="vehicle_id"
                                name="vehicle_id"
                                data-rental-vehicle
                                required
                                class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 {{ $errors->has('vehicle_id') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                            >
                                <option value="">Pilih kendaraan</option>
                                @foreach ($vehicles as $vehicle)
                                    <option
                                        value="{{ $vehicle->id }}"
                                        data-vehicle-type="{{ $vehicle->type->value }}"
                                        data-daily-rate="{{ (float) $vehicle->daily_rate }}"
                                        @selected((string) old('vehicle_id', $selectedVehicleId) === (string) $vehicle->id)
                                    >
                                        {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }}) — {{ $vehicle->type->label() }} · Rp {{ number_format((float) $vehicle->daily_rate, 0, ',', '.') }}/hari
                                    </option>
                                @endforeach
                            </select>
                            @if ($vehicles->isEmpty())
                                <p class="text-xs text-amber-600">Tidak ada kendaraan berstatus tersedia saat ini.</p>
                            @endif
                            @error('vehicle_id')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-input
                            name="start_date"
                            type="date"
                            label="Tanggal Mulai"
                            :value="now()->toDateString()"
                            data-rental-start
                            required
                        />

                        <x-input
                            name="end_date"
                            type="date"
                            label="Tanggal Selesai"
                            :value="now()->toDateString()"
                            data-rental-end
                            required
                        />

                        <div class="space-y-1.5 md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                placeholder="Catatan serah terima atau keperluan penyewa"
                                class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('notes') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                            >{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-card>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <x-button :href="route('rentals.index')" variant="secondary">Batal</x-button>
                    <x-button type="submit" :disabled="$vehicles->isEmpty() || $customers->isEmpty()">Buat Transaksi Rental</x-button>
                </div>
            </div>

            <div>
                <x-card title="Estimasi Biaya Sewa" subtitle="Perhitungan durasi bersifat inklusif">
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Tarif Harian</dt>
                            <dd class="font-medium text-slate-900" data-rental-rate>—</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Estimasi Durasi</dt>
                            <dd class="font-medium text-slate-900" data-rental-days>—</dd>
                        </div>
                        <div class="flex justify-between border-t border-slate-100 pt-3">
                            <dt class="font-semibold text-slate-900">Estimasi Total</dt>
                            <dd class="text-base font-bold text-indigo-600" data-rental-total>—</dd>
                        </div>
                    </dl>

                    <p class="mt-4 rounded-lg bg-slate-50 p-3 text-xs text-slate-600" data-rental-rule>
                        Pilih kendaraan dan rentang tanggal untuk melihat estimasi biaya.
                    </p>
                </x-card>
            </div>
        </form>
    </div>
@endsection
