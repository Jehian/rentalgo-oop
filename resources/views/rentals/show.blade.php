@extends('layouts.app')

@section('title', 'Detail Rental '.$rental->rental_code)

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <a href="{{ route('rentals.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                    ← Kembali ke Transaksi Rental
                </a>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $rental->rental_code }}</h1>
                    <x-status-badge :status="$rental->status" />
                </div>
                <p class="text-sm text-slate-500">
                    Dibuat {{ $rental->created_at?->format('d/m/Y H:i') ?? '—' }}
                    · {{ $rental->user ? 'Diproses oleh '.$rental->user->name : 'Booking mandiri pelanggan' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($rental->status !== \App\Enums\RentalStatus::Cancelled && $remainingBill > 0)
                    <x-button :href="route('payments.create', ['rental_id' => $rental->id])" variant="primary">+ Catat Pembayaran</x-button>
                @endif

                @if ($rental->status === \App\Enums\RentalStatus::Active)
                    <x-button :href="route('rentals.edit', $rental)" variant="secondary">Edit Rental</x-button>
                    <x-button type="button" variant="secondary" data-modal-open="return-rental-modal">Proses Pengembalian</x-button>
                    <x-button type="button" variant="danger" data-modal-open="cancel-rental-modal">Batalkan Rental</x-button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <x-card title="Informasi Rental" subtitle="Pelanggan, kendaraan, dan periode sewa." class="lg:col-span-2">
                <dl class="grid grid-cols-1 gap-5 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-slate-500">Pelanggan</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">
                            @if ($rental->customer && ! $rental->customer->trashed())
                                <a href="{{ route('customers.show', $rental->customer) }}" class="text-indigo-600 hover:text-indigo-500">
                                    {{ $rental->customer->name }}
                                </a>
                            @else
                                {{ $rental->customer?->name ?? 'Pelanggan dihapus' }}
                            @endif
                        </dd>
                        @if ($rental->customer)
                            <p class="mt-0.5 text-xs text-slate-500">{{ $rental->customer->phone }} · NIK: {{ $rental->customer->identity_number }}</p>
                        @endif
                    </div>

                    <div>
                        <dt class="text-xs text-slate-500">Kendaraan</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">
                            @if ($rental->vehicle && ! $rental->vehicle->trashed())
                                <a href="{{ route('vehicles.show', $rental->vehicle) }}" class="text-indigo-600 hover:text-indigo-500">
                                    {{ $rental->vehicle->brand }} {{ $rental->vehicle->model }}
                                </a>
                            @else
                                {{ $rental->vehicle ? $rental->vehicle->brand.' '.$rental->vehicle->model : 'Kendaraan dihapus' }}
                            @endif
                        </dd>
                        @if ($rental->vehicle)
                            <p class="mt-0.5 text-xs text-slate-500">{{ $rental->vehicle->plate_number }} · {{ $rental->vehicle->type->label() }}</p>
                        @endif
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
                        <dd class="mt-0.5 font-semibold text-slate-900">
                            Rp {{ number_format((float) $rental->daily_rate, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-500">Waktu Pengembalian</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">
                            {{ $rental->returned_at?->format('d/m/Y H:i') ?? 'Belum dikembalikan' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-500">Aturan Denda</dt>
                        <dd class="mt-0.5 text-slate-700">
                            {{ (int) ($lateFeeRate * 100) }}% tarif harian / hari terlambat
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs text-slate-500">Catatan</dt>
                        <dd class="mt-0.5 text-slate-700">{{ $rental->notes ?: 'Tidak ada catatan.' }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card title="Ringkasan Tagihan" subtitle="Total sewa, denda, dan status pembayaran">
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
                        <dt class="text-slate-600">Sudah Dibayar (Lunas)</dt>
                        <dd class="font-medium text-emerald-700">Rp {{ number_format($paidAmount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-slate-100 pt-3">
                        <dt class="font-semibold text-slate-900">Sisa Tagihan</dt>
                        <dd class="text-base font-bold {{ $remainingBill > 0 ? 'text-indigo-600' : 'text-emerald-600' }}">
                            Rp {{ number_format($remainingBill, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
            </x-card>
        </div>

        <x-card title="Riwayat Pembayaran" subtitle="Daftar transaksi pembayaran untuk rental ini." :padding="false">
            @if ($rental->status !== \App\Enums\RentalStatus::Cancelled && $remainingBill > 0)
                <x-slot:actions>
                    <x-button :href="route('payments.create', ['rental_id' => $rental->id])" variant="secondary" size="sm">
                        + Catat Pembayaran
                    </x-button>
                </x-slot:actions>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kode</th>
                            <th scope="col" class="px-5 py-3">Metode</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3">Tanggal Lunas</th>
                            <th scope="col" class="px-5 py-3 text-right">Nominal</th>
                            <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rental->payments as $payment)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="whitespace-nowrap px-5 py-3 font-medium text-slate-900">
                                    <a href="{{ route('payments.show', $payment) }}" class="hover:text-indigo-600">
                                        {{ $payment->payment_code }}
                                    </a>
                                </td>
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
                                <td class="whitespace-nowrap px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button :href="route('payments.show', $payment)" variant="secondary" size="sm">Detail</x-button>
                                        @if ($payment->status === \App\Enums\PaymentStatus::Pending)
                                            <form method="POST" action="{{ route('payments.mark-paid', $payment) }}">
                                                @csrf
                                                <x-button type="submit" variant="primary" size="sm">Tandai Lunas</x-button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center">
                                    <p class="text-sm font-medium text-slate-600">Belum ada catatan pembayaran.</p>
                                    <p class="mt-1 text-xs text-slate-400">Catat pembayaran melalui tombol di atas jika pelanggan sudah membayar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    @if ($rental->status === \App\Enums\RentalStatus::Active)
        <x-modal id="return-rental-modal" title="Proses Pengembalian Kendaraan">
            <form id="return-rental-form" method="POST" action="{{ route('rentals.return', $rental) }}" class="space-y-4">
                @csrf
                <p class="text-sm text-slate-600">
                    Kendaraan <span class="font-semibold text-slate-900">{{ $rental->vehicle?->plate_number }}</span> akan ditandai selesai dan kembali tersedia.
                    Denda {{ (int) ($lateFeeRate * 100) }}%/hari dihitung otomatis bila melewati <span class="font-semibold text-slate-900">{{ $rental->end_date->format('d/m/Y') }}</span>.
                </p>

                <x-input
                    name="returned_at"
                    type="datetime-local"
                    label="Waktu Pengembalian"
                    :value="now()->format('Y-m-d\TH:i')"
                    required
                />
            </form>

            <x-slot:footer>
                <x-button type="button" variant="secondary" size="sm" data-modal-close>Batal</x-button>
                <x-button type="submit" form="return-rental-form" variant="primary" size="sm">Selesaikan Rental</x-button>
            </x-slot:footer>
        </x-modal>

        <x-modal id="cancel-rental-modal" title="Batalkan Transaksi Rental?">
            <p class="text-sm text-slate-600">
                Transaksi <span class="font-semibold text-slate-900">{{ $rental->rental_code }}</span> akan dibatalkan,
                status kendaraan kembali <span class="font-semibold text-slate-900">Tersedia</span>, dan seluruh pembayaran
                yang masih berstatus <span class="font-semibold text-slate-900">Menunggu</span> akan ikut dibatalkan.
            </p>

            <x-slot:footer>
                <x-button type="button" variant="secondary" size="sm" data-modal-close>Tutup</x-button>
                <form method="POST" action="{{ route('rentals.cancel', $rental) }}">
                    @csrf
                    <x-button type="submit" variant="danger" size="sm">Ya, Batalkan</x-button>
                </form>
            </x-slot:footer>
        </x-modal>
    @endif
@endsection
