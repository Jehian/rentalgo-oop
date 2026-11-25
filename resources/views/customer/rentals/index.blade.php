@extends('layouts.public')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pesanan Saya</h1>
                <p class="text-sm text-slate-500">Daftar transaksi rental dan status pembayaran Anda.</p>
            </div>

            <x-button :href="route('home')" variant="primary" size="sm">Sewa Kendaraan Lain</x-button>
        </div>

        <x-card>
            <form method="GET" action="{{ route('customer.rentals.index') }}" class="flex flex-wrap items-end gap-3">
                <div class="w-full sm:w-64">
                    <label for="status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Filter Status</label>
                    <select id="status" name="status" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">Semua Status</option>
                        @foreach ($rentalStatuses as $status)
                            <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <x-button type="submit" variant="secondary" size="sm">Filter</x-button>
                @if ($selectedStatus !== '')
                    <x-button :href="route('customer.rentals.index')" variant="ghost" size="sm">Reset</x-button>
                @endif
            </form>
        </x-card>

        <x-card :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kode</th>
                            <th scope="col" class="px-5 py-3">Kendaraan</th>
                            <th scope="col" class="px-5 py-3">Tanggal Sewa</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3 text-right">Total Tagihan</th>
                            <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rentals as $rental)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="whitespace-nowrap px-5 py-3 font-medium text-slate-900">{{ $rental->rental_code }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-700">
                                    @if ($rental->vehicle)
                                        {{ $rental->vehicle->brand }} {{ $rental->vehicle->model }}
                                        <span class="text-slate-400">({{ $rental->vehicle->plate_number }})</span>
                                    @else
                                        Kendaraan dihapus
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                    {{ $rental->start_date->format('d/m/Y') }} – {{ $rental->end_date->format('d/m/Y') }}
                                    <span class="text-xs text-slate-400">({{ $rental->duration_days }} hari)</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <x-status-badge :status="$rental->status" />
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right font-medium text-slate-900">
                                    Rp {{ number_format($rental->totalBill(), 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right">
                                    <x-button :href="route('customer.rentals.show', $rental)" variant="secondary" size="sm">Detail</x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600">Belum ada transaksi rental.</p>
                                    <p class="mt-1 text-xs text-slate-400">Pilih kendaraan di katalog untuk mulai menyewa.</p>
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
