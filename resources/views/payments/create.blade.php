@extends('layouts.app')

@section('title', 'Catat Pembayaran')

@section('content')
    @php
        $preselectedRental = $rentals->firstWhere('id', (int) $selectedRentalId);
        $defaultAmount = $preselectedRental ? (float) $preselectedRental->getAttribute('remaining_bill') : null;
    @endphp

    <div class="space-y-6">
        <div>
            <a href="{{ route('payments.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                ← Kembali ke Pembayaran
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Catat Pembayaran</h1>
            <p class="text-sm text-slate-500">Tambahkan pembayaran untuk rental aktif atau selesai. Nominal tidak boleh melebihi sisa tagihan.</p>
        </div>

        <form method="POST" action="{{ route('payments.store') }}" data-payment-form class="space-y-6">
            @csrf

            <x-card title="Detail Pembayaran" subtitle="Pilih transaksi rental dan masukkan nominal pembayaran.">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="rental_id" class="block text-sm font-medium text-slate-700">Transaksi Rental</label>
                        <select
                            id="rental_id"
                            name="rental_id"
                            data-payment-rental-select
                            required
                            class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 {{ $errors->has('rental_id') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                        >
                            <option value="">Pilih transaksi rental</option>
                            @foreach ($rentals as $rental)
                                @php($remaining = (float) $rental->getAttribute('remaining_bill'))
                                <option
                                    value="{{ $rental->id }}"
                                    data-remaining="{{ $remaining }}"
                                    @selected((string) old('rental_id', $selectedRentalId) === (string) $rental->id)
                                >
                                    {{ $rental->rental_code }} — {{ $rental->customer?->name ?? 'Pelanggan dihapus' }}
                                    ({{ $rental->vehicle?->plate_number ?? '—' }}) · Sisa Rp {{ number_format($remaining, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500" data-payment-remaining>Pilih transaksi rental untuk melihat sisa tagihan.</p>
                        @if ($rentals->isEmpty())
                            <p class="text-xs text-amber-600">Tidak ada transaksi rental dengan sisa tagihan.</p>
                        @endif
                        @error('rental_id')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-input
                        name="amount"
                        type="number"
                        step="0.01"
                        min="1"
                        label="Nominal (Rp)"
                        :value="$defaultAmount && $defaultAmount > 0 ? $defaultAmount : null"
                        placeholder="Contoh: 350000"
                        data-payment-amount
                        required
                    />

                    <div class="space-y-1.5">
                        <label for="method" class="block text-sm font-medium text-slate-700">Metode Pembayaran</label>
                        <select
                            id="method"
                            name="method"
                            required
                            class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 {{ $errors->has('method') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                        >
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->value }}" @selected(old('method', \App\Enums\PaymentMethod::Cash->value) === $method->value)>
                                    {{ $method->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('method')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="status" class="block text-sm font-medium text-slate-700">Status Awal</label>
                        <select
                            id="status"
                            name="status"
                            data-payment-status
                            required
                            class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 {{ $errors->has('status') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                        >
                            @foreach ($paymentStatuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status', \App\Enums\PaymentStatus::Pending->value) === $status->value)>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div data-paid-at-wrapper class="{{ old('status') === \App\Enums\PaymentStatus::Paid->value ? '' : 'hidden' }}">
                        <x-input
                            name="paid_at"
                            type="datetime-local"
                            label="Waktu Lunas (opsional)"
                            hint="Kosongkan untuk memakai waktu saat ini."
                        />
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            placeholder="Contoh: Pembayaran DP atau pelunasan akhir"
                            class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('notes') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-card>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <x-button :href="route('payments.index')" variant="secondary">Batal</x-button>
                <x-button type="submit" :disabled="$rentals->isEmpty()">Simpan Pembayaran</x-button>
            </div>
        </form>
    </div>
@endsection
