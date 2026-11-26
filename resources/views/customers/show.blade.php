@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <a href="{{ route('customers.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                    ← Kembali ke Pelanggan
                </a>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ $customer->name }}</h1>
                <p class="text-sm text-slate-500">Terdaftar {{ $customer->created_at?->format('d/m/Y') ?? '—' }}</p>
            </div>

            <div class="flex items-center gap-2">
                <x-button :href="route('customers.edit', $customer)" variant="secondary">Edit</x-button>

                @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                    <x-button type="button" variant="danger" data-modal-open="delete-customer-modal">Hapus</x-button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Rental</p>
                <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($customer->rentals_count, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Rental Aktif</p>
                <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($customer->active_rentals_count, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Status Data</p>
                <p class="mt-2 text-sm font-semibold text-emerald-600">Aktif</p>
            </div>
        </div>

        <x-card title="Informasi Pelanggan" subtitle="Data identitas dan kontak.">
            <dl class="grid grid-cols-1 gap-5 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-xs text-slate-500">Email</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $customer->email ?? 'Belum diisi' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">Nomor Telepon</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $customer->phone }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">NIK / Nomor KTP</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $customer->identity_number }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">Nomor SIM</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $customer->license_number }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs text-slate-500">Alamat</dt>
                    <dd class="mt-0.5 text-slate-700">{{ $customer->address ?? 'Belum diisi' }}</dd>
                </div>
            </dl>
        </x-card>

        <x-card title="Riwayat Rental" subtitle="Transaksi rental pelanggan ini." :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kode</th>
                            <th scope="col" class="px-5 py-3">Kendaraan</th>
                            <th scope="col" class="px-5 py-3">Periode</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3 text-right">Total Tagihan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rentals as $rental)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="whitespace-nowrap px-5 py-3 font-medium text-slate-900">{{ $rental->rental_code }}</td>
                                <td class="px-5 py-3 text-slate-700">
                                    @if ($rental->vehicle)
                                        {{ $rental->vehicle->brand }} {{ $rental->vehicle->model }}
                                        <span class="text-xs text-slate-400">({{ $rental->vehicle->plate_number }})</span>
                                    @else
                                        Kendaraan dihapus
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $rental->start_date->format('d/m/Y') }} – {{ $rental->end_date->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <x-status-badge :status="$rental->status" />
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right font-medium text-slate-900">
                                    Rp {{ number_format($rental->totalBill(), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600">Belum ada riwayat rental.</p>
                                    <p class="mt-1 text-xs text-slate-400">Transaksi pelanggan ini akan tampil di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-pagination :paginator="$rentals" />
    </div>

    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
        <x-modal id="delete-customer-modal" title="Hapus Pelanggan?">
            <p class="text-sm text-slate-600">
                Pelanggan <span class="font-semibold text-slate-900">{{ $customer->name }}</span> akan dihapus.
                Riwayat rental tetap tersimpan, tetapi data pelanggan tidak akan tampil di daftar aktif.
            </p>

            <x-slot:footer>
                <x-button type="button" variant="secondary" size="sm" data-modal-close>Batal</x-button>
                <form method="POST" action="{{ route('customers.destroy', $customer) }}">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" size="sm">Ya, Hapus</x-button>
                </form>
            </x-slot:footer>
        </x-modal>
    @endif
@endsection
