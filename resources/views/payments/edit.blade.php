@extends('layouts.app')

@section('title', 'Edit Pembayaran '.$payment->payment_code)

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('payments.show', $payment) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                ← Kembali ke Detail Pembayaran
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Edit Pembayaran {{ $payment->payment_code }}</h1>
            <p class="text-sm text-slate-500">Hanya pembayaran berstatus menunggu yang dapat diubah.</p>
        </div>

        <form method="POST" action="{{ route('payments.update', $payment) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <x-card title="Informasi Rental Terkait" subtitle="Sisa tagihan rental yang belum lunas: Rp {{ number_format($remainingBill, 0, ',', '.') }}.">
                <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-3">
                    <div>
                        <dt class="text-xs text-slate-500">Kode Rental</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $payment->rental?->rental_code ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Pelanggan</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $payment->rental?->customer?->name ?? 'Pelanggan dihapus' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Kendaraan</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">
                            {{ $payment->rental?->vehicle ? $payment->rental->vehicle->brand.' '.$payment->rental->vehicle->model.' ('.$payment->rental->vehicle->plate_number.')' : 'Kendaraan dihapus' }}
                        </dd>
                    </div>
                </dl>
            </x-card>

            <x-card title="Ubah Data Pembayaran">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <x-input
                        name="amount"
                        type="number"
                        step="0.01"
                        min="1"
                        label="Nominal (Rp)"
                        :value="(float) $payment->amount"
                        hint="Maksimal Rp {{ number_format($remainingBill, 0, ',', '.') }}."
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
                                <option value="{{ $method->value }}" @selected(old('method', $payment->method->value) === $method->value)>
                                    {{ $method->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('method')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('notes') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                        >{{ old('notes', $payment->notes) }}</textarea>
                        @error('notes')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-card>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <x-button :href="route('payments.show', $payment)" variant="secondary">Batal</x-button>
                <x-button type="submit">Simpan Perubahan</x-button>
            </div>
        </form>
    </div>
@endsection
