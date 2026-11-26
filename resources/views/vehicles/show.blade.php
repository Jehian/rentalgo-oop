@extends('layouts.app')

@section('title', 'Detail Kendaraan')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <a href="{{ route('vehicles.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                    ← Kembali ke Kendaraan
                </a>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $vehicle->brand }} {{ $vehicle->model }}</h1>
                    <x-status-badge :status="$vehicle->status" />
                </div>
                <p class="text-sm text-slate-500">{{ $vehicle->plate_number }} · {{ $vehicle->type->label() }} · {{ $vehicle->year }}</p>
            </div>

            <div class="flex items-center gap-2">
                <x-button :href="route('vehicles.edit', $vehicle)" variant="secondary">Edit</x-button>

                @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                    <x-button type="button" variant="danger" data-modal-open="delete-vehicle-modal">Hapus</x-button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Rental</p>
                <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($vehicle->rentals_count, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Rental Aktif</p>
                <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($vehicle->active_rentals_count, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Tarif Harian</p>
                <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Rp {{ number_format((float) $vehicle->daily_rate, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <x-card title="Foto Kendaraan" subtitle="Dokumentasi visual armada.">
                @if ($vehicle->image_path)
                    <img src="{{ asset('storage/'.$vehicle->image_path) }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}" class="aspect-[4/3] w-full rounded-xl object-cover ring-1 ring-slate-200">
                @else
                    <div class="flex aspect-[4/3] w-full items-center justify-center rounded-xl bg-slate-100 text-slate-300">
                        <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                        </svg>
                    </div>
                @endif
            </x-card>

            <x-card title="Informasi Kendaraan" subtitle="Spesifikasi dan data operasional." class="lg:col-span-2">
                <dl class="grid grid-cols-1 gap-5 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-slate-500">Nomor Polisi</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $vehicle->plate_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Tipe</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $vehicle->type->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Tahun</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $vehicle->year }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Tarif Harian</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">Rp {{ number_format((float) $vehicle->daily_rate, 0, ',', '.') }}</dd>
                    </div>

                    @if ($vehicle->type === \App\Enums\VehicleType::Car)
                        <div>
                            <dt class="text-xs text-slate-500">Jumlah Kursi</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">{{ $vehicle->seat_count }} kursi</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Transmisi</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">{{ ucfirst((string) $vehicle->transmission) }}</dd>
                        </div>
                    @else
                        <div>
                            <dt class="text-xs text-slate-500">Kapasitas Mesin</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">{{ $vehicle->engine_cc }} cc</dd>
                        </div>
                    @endif

                    <div class="sm:col-span-2">
                        <dt class="text-xs text-slate-500">Deskripsi</dt>
                        <dd class="mt-0.5 text-slate-700">{{ $vehicle->description ?? 'Belum ada deskripsi.' }}</dd>
                    </div>
                </dl>
            </x-card>
        </div>

        <x-card title="Riwayat Rental" subtitle="Transaksi rental yang menggunakan kendaraan ini." :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kode</th>
                            <th scope="col" class="px-5 py-3">Pelanggan</th>
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
                                    {{ $rental->customer?->name ?? 'Pelanggan dihapus' }}
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
                                    <p class="mt-1 text-xs text-slate-400">Transaksi kendaraan ini akan tampil di sini.</p>
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
        <x-modal id="delete-vehicle-modal" title="Hapus Kendaraan?">
            <p class="text-sm text-slate-600">
                Kendaraan <span class="font-semibold text-slate-900">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})</span>
                akan dihapus beserta fotonya. Riwayat rental tetap tersimpan.
            </p>

            <x-slot:footer>
                <x-button type="button" variant="secondary" size="sm" data-modal-close>Batal</x-button>
                <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" size="sm">Ya, Hapus</x-button>
                </form>
            </x-slot:footer>
        </x-modal>
    @endif
@endsection
