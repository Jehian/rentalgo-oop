@extends('layouts.app')

@section('title', 'Detail Pembayaran '.$payment->payment_code)

@section('content')
    @php
        $rental = $payment->rental;
        $totalBill = $rental?->totalBill() ?? 0.0;
        $paidAmount = $rental?->paidAmount() ?? 0.0;
    @endphp

    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <a href="{{ route('payments.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                    ← Kembali ke Pembayaran
                </a>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $payment->payment_code }}</h1>
                    <x-status-badge :status="$payment->status" />
                </div>
                <p class="text-sm text-slate-500">
                    Dicatat {{ $payment->created_at?->format('d/m/Y H:i') ?? '—' }}
                </p>
            </div>

            @if ($payment->status === \App\Enums\PaymentStatus::Pending)
                <div class="flex flex-wrap items-center gap-2">
                    <form method="POST" action="{{ route('payments.mark-paid', $payment) }}">
                        @csrf
                        <x-button type="submit" variant="primary">Tandai Lunas</x-button>
                    </form>

                    <form method="POST" action="{{ route('payments.mark-failed', $payment) }}">
                        @csrf
                        <x-button type="submit" variant="secondary">Tandai Gagal</x-button>
                    </form>

                    <x-button :href="route('payments.edit', $payment)" variant="secondary">Edit</x-button>
                    <x-button type="button" variant="danger" data-modal-open="delete-payment-modal">Hapus</x-button>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <x-card title="Informasi Pembayaran" subtitle="Rincian transaksi pembayaran." class="lg:col-span-2">
                <dl class="grid grid-cols-1 gap-5 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-slate-500">Kode Pembayaran</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $payment->payment_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Nominal</dt>
                        <dd class="mt-0.5 text-base font-bold text-slate-900">
                            Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Metode Pembayaran</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $payment->method->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Status</dt>
                        <dd class="mt-0.5">
                            <x-status-badge :status="$payment->status" />
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Waktu Lunas</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">
                            {{ $payment->paid_at?->format('d/m/Y H:i') ?? 'Belum lunas' }}
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-slate-500">Catatan</dt>
                        <dd class="mt-0.5 text-slate-700">{{ $payment->notes ?: 'Tidak ada catatan.' }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card title="Ringkasan Rental Terkait" subtitle="Tagihan dan status rental.">
                @if ($rental)
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Kode Rental</dt>
                            <dd class="font-semibold">
                                <a href="{{ route('rentals.show', $rental) }}" class="text-indigo-600 hover:text-indigo-500">
                                    {{ $rental->rental_code }}
                                </a>
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Pelanggan</dt>
                            <dd class="font-medium text-slate-900">{{ $rental->customer?->name ?? 'Pelanggan dihapus' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Kendaraan</dt>
                            <dd class="font-medium text-slate-900">{{ $rental->vehicle?->plate_number ?? 'Kendaraan dihapus' }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-slate-100 pt-3">
                            <dt class="text-slate-600">Total Tagihan</dt>
                            <dd class="font-medium text-slate-900">Rp {{ number_format($totalBill, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Sudah Dibayar</dt>
                            <dd class="font-medium text-emerald-700">Rp {{ number_format($paidAmount, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-slate-100 pt-3">
                            <dt class="font-semibold text-slate-900">Sisa Tagihan</dt>
                            <dd class="font-bold {{ $remainingBill > 0 ? 'text-indigo-600' : 'text-emerald-600' }}">
                                Rp {{ number_format($remainingBill, 0, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-slate-500">Data rental tidak ditemukan.</p>
                @endif
            </x-card>
        </div>
    </div>

    @if ($payment->status === \App\Enums\PaymentStatus::Pending)
        <x-modal id="delete-payment-modal" title="Hapus Pembayaran?">
            <p class="text-sm text-slate-600">
                Pembayaran <span class="font-semibold text-slate-900">{{ $payment->payment_code }}</span> senilai
                <span class="font-semibold text-slate-900">Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</span>
                akan dihapus permanen.
            </p>

            <x-slot:footer>
                <x-button type="button" variant="secondary" size="sm" data-modal-close>Batal</x-button>
                <form method="POST" action="{{ route('payments.destroy', $payment) }}">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" size="sm">Ya, Hapus</x-button>
                </form>
            </x-slot:footer>
        </x-modal>
    @endif
@endsection
