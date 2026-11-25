@extends('layouts.public')

@section('title', $vehicle->brand.' '.$vehicle->model)

@section('content')
    <div class="space-y-6">
        <a href="{{ route('home') }}" class="inline-block text-sm text-slate-600 hover:text-slate-900">
            ← Kembali ke Katalog
        </a>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-2">
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="aspect-[16/9] bg-slate-100">
                        @if ($vehicle->image_path)
                            <img
                                src="{{ asset('storage/'.$vehicle->image_path) }}"
                                alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-sm text-slate-400">
                                {{ $vehicle->type->label() }}
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-medium text-slate-500">{{ $vehicle->type->label() }}</span>
                                    <x-status-badge :status="$vehicle->status" />
                                </div>
                                <h1 class="mt-1 text-xl font-bold text-slate-900">{{ $vehicle->brand }} {{ $vehicle->model }}</h1>
                                <p class="text-sm text-slate-500">{{ $vehicle->plate_number }} · Tahun {{ $vehicle->year }}</p>
                            </div>

                            <p class="text-lg font-bold text-slate-900">
                                Rp {{ number_format((float) $vehicle->daily_rate, 0, ',', '.') }}
                                <span class="text-xs font-normal text-slate-500">/hari</span>
                            </p>
                        </div>

                        <dl class="grid grid-cols-2 gap-3 border-t border-slate-100 pt-4 text-sm sm:grid-cols-3">
                            <div>
                                <dt class="text-xs text-slate-500">Tahun</dt>
                                <dd class="font-medium text-slate-900">{{ $vehicle->year }}</dd>
                            </div>

                            @if ($vehicle->type === \App\Enums\VehicleType::Car)
                                <div>
                                    <dt class="text-xs text-slate-500">Kursi</dt>
                                    <dd class="font-medium text-slate-900">{{ $vehicle->seat_count }} Kursi</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-500">Transmisi</dt>
                                    <dd class="font-medium text-slate-900">{{ ucfirst((string) $vehicle->transmission) }}</dd>
                                </div>
                            @else
                                <div>
                                    <dt class="text-xs text-slate-500">Kapasitas Mesin</dt>
                                    <dd class="font-medium text-slate-900">{{ $vehicle->engine_cc }} cc</dd>
                                </div>
                            @endif
                        </dl>

                        @if ($vehicle->description)
                            <p class="border-t border-slate-100 pt-4 text-sm text-slate-600">{{ $vehicle->description }}</p>
                        @endif

                        <p class="rounded-lg bg-slate-50 p-3 text-xs text-slate-600">
                            @if ($vehicle->type === \App\Enums\VehicleType::Car)
                                Total sewa mobil = (tarif harian × durasi inklusif) + asuransi {{ (int) ($pricingConfig['car_insurance_rate'] * 100) }}%.
                            @else
                                Total sewa motor = tarif harian × durasi inklusif (diskon {{ (int) ($pricingConfig['motorcycle_weekly_discount_rate'] * 100) }}% untuk minimal {{ $pricingConfig['motorcycle_weekly_min_days'] }} hari).
                            @endif
                        </p>

                        @if ($activeRentals->isNotEmpty())
                            <div class="border-t border-slate-100 pt-4">
                                <p class="text-xs font-semibold text-slate-700">Jadwal Terpakai:</p>
                                <ul class="mt-1 space-y-0.5 text-xs text-slate-600">
                                    @foreach ($activeRentals as $active)
                                        <li>• {{ $active->start_date->format('d/m/Y') }} – {{ $active->end_date->format('d/m/Y') }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <x-card title="Form Sewa" subtitle="Durasi dihitung inklusif">
                    <div
                        data-booking-estimator
                        data-vehicle-type="{{ $vehicle->type->value }}"
                        data-daily-rate="{{ (float) $vehicle->daily_rate }}"
                        data-car-insurance-rate="{{ $pricingConfig['car_insurance_rate'] }}"
                        data-motorcycle-discount-rate="{{ $pricingConfig['motorcycle_weekly_discount_rate'] }}"
                        data-motorcycle-min-days="{{ $pricingConfig['motorcycle_weekly_min_days'] }}"
                        class="space-y-4"
                    >
                        @if (! $vehicle->isAvailable())
                            <x-alert
                                type="warning"
                                message="Kendaraan sedang berstatus {{ $vehicle->status->label() }}."
                            />
                        @endif

                        @if (! auth('customer')->check())
                            <p class="text-sm text-slate-600">Masuk atau daftar sebagai pelanggan untuk menyewa kendaraan ini.</p>
                            <div class="flex gap-2">
                                <x-button :href="route('customer.login')" variant="primary" size="sm" class="flex-1">Masuk</x-button>
                                <x-button :href="route('customer.register')" variant="secondary" size="sm" class="flex-1">Daftar</x-button>
                            </div>
                        @else
                            <form method="POST" action="{{ route('customer.rentals.store', $vehicle) }}" class="space-y-4">
                                @csrf

                                <x-input
                                    name="start_date"
                                    label="Tanggal Mulai"
                                    type="date"
                                    :value="now()->toDateString()"
                                    min="{{ now()->toDateString() }}"
                                    required
                                    data-booking-start
                                />

                                <x-input
                                    name="end_date"
                                    label="Tanggal Selesai"
                                    type="date"
                                    :value="now()->toDateString()"
                                    min="{{ now()->toDateString() }}"
                                    required
                                    data-booking-end
                                />

                                <div class="space-y-1.5">
                                    <label for="payment_method" class="block text-sm font-medium text-slate-700">Metode Pembayaran (Opsional)</label>
                                    <select
                                        id="payment_method"
                                        name="payment_method"
                                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                    >
                                        <option value="">Bayar nanti</option>
                                        @foreach (\App\Enums\PaymentMethod::cases() as $method)
                                            <option value="{{ $method->value }}" @selected(old('payment_method') === $method->value)>
                                                {{ $method->label() }} (Pending)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_method')
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label for="notes" class="block text-sm font-medium text-slate-700">Catatan (Opsional)</label>
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        rows="2"
                                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                    >{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm">
                                    <div class="flex justify-between text-slate-600">
                                        <span>Estimasi Durasi</span>
                                        <span class="font-medium text-slate-900" data-estimate-days>1 hari</span>
                                    </div>
                                    <div class="mt-1.5 flex justify-between border-t border-slate-200 pt-1.5">
                                        <span class="font-medium text-slate-700">Estimasi Total</span>
                                        <span class="font-bold text-slate-900" data-estimate-total>
                                            Rp {{ number_format((float) \App\Domain\Vehicles\VehicleFactory::fromModel($vehicle)->calculateRentalCost(1), 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <x-button type="submit" class="w-full" :disabled="! $vehicle->isAvailable()">
                                    Ajukan Sewa
                                </x-button>
                            </form>
                        @endif
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection
