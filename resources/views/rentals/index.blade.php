@extends('layouts.app')

@section('title', 'Transaksi Rental')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Transaksi Rental</h1>
                <p class="text-sm text-slate-500">Kelola penyewaan kendaraan, perpanjangan, pengembalian, dan pembatalan.</p>
            </div>

            <x-button :href="route('rentals.create')">+ Buat Rental</x-button>
        </div>

        <x-card>
            <form method="GET" action="{{ route('rentals.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-6">
                <div class="md:col-span-2">
                    <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Cari</label>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ $filters['search'] }}"
                        placeholder="Kode rental, pelanggan, atau no. polisi"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Status</label>
                    <select id="status" name="status" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">Semua Status</option>
                        @foreach ($rentalStatuses as $status)
                            <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_from" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Mulai Dari</label>
                    <input
                        id="date_from"
                        name="date_from"
                        type="date"
                        value="{{ $filters['date_from'] }}"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="date_to" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Sampai</label>
                    <input
                        id="date_to"
                        name="date_to"
                        type="date"
                        value="{{ $filters['date_to'] }}"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="sort" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutkan</label>
                    <select id="sort" name="sort" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Terbaru</option>
                        <option value="oldest" @selected($filters['sort'] === 'oldest')>Terlama</option>
                        <option value="start_desc" @selected($filters['sort'] === 'start_desc')>Tgl Mulai Terbaru</option>
                        <option value="start_asc" @selected($filters['sort'] === 'start_asc')>Tgl Mulai Terlama</option>
                        <option value="total_desc" @selected($filters['sort'] === 'total_desc')>Total Termahal</option>
                        <option value="total_asc" @selected($filters['sort'] === 'total_asc')>Total Termurah</option>
                    </select>
                </div>

                <div class="flex items-end gap-2 md:col-span-6">
                    <x-button type="submit" variant="secondary" size="sm">Filter</x-button>
                    @if ($filters['search'] !== '' || $filters['status'] !== '' || $filters['date_from'] !== '' || $filters['date_to'] !== '' || $filters['sort'] !== 'newest')
                        <x-button :href="route('rentals.index')" variant="ghost" size="sm">Reset</x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <x-card :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kode</th>
                            <th scope="col" class="px-5 py-3">Pelanggan</th>
                            <th scope="col" class="px-5 py-3">Kendaraan</th>
                            <th scope="col" class="px-5 py-3">Periode</th>
                            <th scope="col" class="px-5 py-3 text-right">Total Tagihan</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rentals as $rental)
                            @php
                                $totalBill = $rental->totalBill();
                                $paidAmount = (float) ($rental->paid_amount_sum ?? 0);
                            @endphp
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="whitespace-nowrap px-5 py-3">
                                    <a href="{{ route('rentals.show', $rental) }}" class="font-medium text-slate-900 hover:text-indigo-600">
                                        {{ $rental->rental_code }}
                                    </a>
                                    <p class="text-xs text-slate-500">
                                        {{ $rental->user ? 'Oleh '.$rental->user->name : 'Booking mandiri' }}
                                    </p>
                                </td>
                                <td class="px-5 py-3 text-slate-700">
                                    <p class="font-medium text-slate-900">{{ $rental->customer?->name ?? 'Pelanggan dihapus' }}</p>
                                    @if ($rental->customer?->phone)
                                        <p class="text-xs text-slate-500">{{ $rental->customer->phone }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-700">
                                    @if ($rental->vehicle)
                                        <p class="font-medium text-slate-900">{{ $rental->vehicle->brand }} {{ $rental->vehicle->model }}</p>
                                        <p class="text-xs text-slate-500">{{ $rental->vehicle->plate_number }}</p>
                                    @else
                                        <span class="text-slate-400">Kendaraan dihapus</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    <p>{{ $rental->start_date->format('d/m/Y') }} – {{ $rental->end_date->format('d/m/Y') }}</p>
                                    <p class="text-xs text-slate-500">{{ $rental->duration_days }} hari</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right">
                                    <p class="font-medium text-slate-900">Rp {{ number_format($totalBill, 0, ',', '.') }}</p>
                                    <p class="text-xs {{ $paidAmount >= $totalBill ? 'text-emerald-600' : 'text-slate-500' }}">
                                        Lunas: Rp {{ number_format($paidAmount, 0, ',', '.') }}
                                    </p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <x-status-badge :status="$rental->status" />
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button :href="route('rentals.show', $rental)" variant="secondary" size="sm">Detail</x-button>

                                        @if ($rental->status === \App\Enums\RentalStatus::Active)
                                            <x-button :href="route('rentals.edit', $rental)" variant="ghost" size="sm">Edit</x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600">Belum ada transaksi rental.</p>
                                    <p class="mt-1 text-xs text-slate-400">Buat transaksi rental pertama untuk menyewakan kendaraan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-pagination :paginator="$rentals" />
    </div>
@endsection
