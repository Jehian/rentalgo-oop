@extends('layouts.public')

@section('title', 'Detail Pesanan '.$rental->rental_code)

@section('content')
    @php
        $totalBill = $rental->totalBill();
        $paidAmount = $rental->paidAmount();
        $remaining = max(0, $totalBill - $paidAmount);
    @endphp

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('customer.rentals.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                    ← Kembali ke Pesanan Saya
                </a>
                <div class="mt-2 flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $rental->rental_code }}</h1>
                    <x-status-badge :status="$rental->status" />
                </div>
            </div>

            @if ($rental->status === \App\Enums\RentalStatus::Active)
                <x-button type="button" variant="danger" size="sm" data-modal-open="cancel-rental-modal">
                    Batalkan Rental
                </x-button>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <x-card title="Informasi Rental" class="lg:col-span-2">
                <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
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
                        <dt class="text-xs text-slate-500">Periode Sewa</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">
                            {{ $rental->start_date->format('d/m/Y') }} – {{ $rental->end_date->format('d/m/Y') }}
                            ({{ $rental->duration_days }} hari)
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Tarif Harian (Snapshot)</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">Rp {{ number_format((float) $rental->daily_rate, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Waktu Pengembalian</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">
                            {{ $rental->returned_at?->format('d/m/Y H:i') ?? 'Belum dikembalikan' }}
                        </dd>
                    </div>
                    @if ($rental->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-xs text-slate-500">Catatan</dt>
                            <dd class="mt-0.5 text-slate-700">{{ $rental->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </x-card>

            <x-card title="Ringkasan Tagihan">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Biaya Sewa</dt>
                        <dd class="font-medium text-slate-900">Rp {{ number_format((float) $rental->total_amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Denda Keterlambatan</dt>
                        <dd class="font-medium text-slate-900">Rp {{ number_format((float) $rental->late_fee, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-slate-100 pt-3">
                        <dt class="font-semibold text-slate-900">Total Tagihan</dt>
                        <dd class="font-bold text-slate-900">Rp {{ number_format($totalBill, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Sudah Dibayar</dt>
                        <dd class="font-medium text-emerald-700">Rp {{ number_format($paidAmount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-slate-100 pt-3">
                        <dt class="font-semibold text-slate-900">Sisa Tagihan</dt>
                        <dd class="font-bold text-indigo-600">Rp {{ number_format($remaining, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </x-card>
        </div>

        <x-card title="Riwayat Pembayaran" :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kode</th>
                            <th scope="col" class="px-5 py-3">Metode</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3">Tanggal Lunas</th>
                            <th scope="col" class="px-5 py-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rental->payments as $payment)
                            <tr>
                                <td class="whitespace-nowrap px-5 py-3 font-medium text-slate-900">{{ $payment->payment_code }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-700">{{ $payment->method->label() }}</td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <x-status-badge :status="$payment->status" />
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $payment->paid_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right font-medium text-slate-900">
                                    Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-500">
                                    Belum ada catatan pembayaran. Pembayaran dapat diproses oleh staff saat pengambilan atau pengembalian kendaraan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    @if ($rental->status === \App\Enums\RentalStatus::Active)
        <x-modal id="cancel-rental-modal" title="Batalkan Pesanan Rental?">
            <p class="text-sm text-slate-600">
                Pesanan <span class="font-semibold text-slate-900">{{ $rental->rental_code }}</span> akan dibatalkan,
                kendaraan akan kembali tersedia, dan pembayaran yang masih pending akan dibatalkan.
            </p>

            <x-slot:footer>
                <x-button type="button" variant="secondary" size="sm" data-modal-close>Tutup</x-button>
                <form method="POST" action="{{ route('customer.rentals.cancel', $rental) }}">
                    @csrf
                    <x-button type="submit" variant="danger" size="sm">Ya, Batalkan</x-button>
                </form>
            </x-slot:footer>
        </x-modal>
    @endif
@endsection
