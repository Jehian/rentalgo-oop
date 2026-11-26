@extends('layouts.app')

@section('title', 'Kendaraan')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kendaraan</h1>
                <p class="text-sm text-slate-500">Kelola armada, tarif, status, dan foto kendaraan.</p>
            </div>

            <x-button :href="route('vehicles.create')">+ Tambah Kendaraan</x-button>
        </div>

        <x-card>
            <form method="GET" action="{{ route('vehicles.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-5">
                <div class="md:col-span-2">
                    <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Cari</label>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ $filters['search'] }}"
                        placeholder="Nomor polisi, merek, atau model"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="type" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipe</label>
                    <select id="type" name="type" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">Semua Tipe</option>
                        @foreach ($vehicleTypes as $type)
                            <option value="{{ $type->value }}" @selected($filters['type'] === $type->value)>{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Status</label>
                    <select id="status" name="status" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">Semua Status</option>
                        @foreach ($vehicleStatuses as $status)
                            <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutkan</label>
                    <select id="sort" name="sort" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Terbaru</option>
                        <option value="oldest" @selected($filters['sort'] === 'oldest')>Terlama</option>
                        <option value="rate_asc" @selected($filters['sort'] === 'rate_asc')>Tarif Termurah</option>
                        <option value="rate_desc" @selected($filters['sort'] === 'rate_desc')>Tarif Termahal</option>
                        <option value="year_desc" @selected($filters['sort'] === 'year_desc')>Tahun Terbaru</option>
                        <option value="brand_asc" @selected($filters['sort'] === 'brand_asc')>Merek A–Z</option>
                    </select>
                </div>

                <div class="flex items-end gap-2 md:col-span-5">
                    <x-button type="submit" variant="secondary" size="sm">Filter</x-button>
                    @if ($filters['search'] !== '' || $filters['type'] !== '' || $filters['status'] !== '' || $filters['sort'] !== 'newest')
                        <x-button :href="route('vehicles.index')" variant="ghost" size="sm">Reset</x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <x-card :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kendaraan</th>
                            <th scope="col" class="px-5 py-3">Tipe</th>
                            <th scope="col" class="px-5 py-3">Tahun</th>
                            <th scope="col" class="px-5 py-3 text-right">Tarif Harian</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3">Rental Aktif</th>
                            <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($vehicles as $vehicle)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-16 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                            @if ($vehicle->image_path)
                                                <img src="{{ asset('storage/'.$vehicle->image_path) }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-slate-300">
                                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('vehicles.show', $vehicle) }}" class="font-medium text-slate-900 hover:text-indigo-600">
                                                {{ $vehicle->brand }} {{ $vehicle->model }}
                                            </a>
                                            <p class="text-xs text-slate-500">{{ $vehicle->plate_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-700">{{ $vehicle->type->label() }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-600">{{ $vehicle->year }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-right font-medium text-slate-900">
                                    Rp {{ number_format((float) $vehicle->daily_rate, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <x-status-badge :status="$vehicle->status" />
                                </td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    @if ($vehicle->active_rentals_count > 0)
                                        <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-600/20">
                                            {{ $vehicle->active_rentals_count }} aktif
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button :href="route('vehicles.show', $vehicle)" variant="secondary" size="sm">Detail</x-button>
                                        <x-button :href="route('vehicles.edit', $vehicle)" variant="ghost" size="sm">Edit</x-button>

                                        @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                                            <x-button
                                                type="button"
                                                variant="danger"
                                                size="sm"
                                                data-modal-open="delete-vehicle-{{ $vehicle->id }}"
                                            >
                                                Hapus
                                            </x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600">Belum ada kendaraan.</p>
                                    <p class="mt-1 text-xs text-slate-400">Tambahkan kendaraan pertama untuk mulai menyusun armada.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-pagination :paginator="$vehicles" />
    </div>

    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
        @foreach ($vehicles as $vehicle)
            <x-modal id="delete-vehicle-{{ $vehicle->id }}" title="Hapus Kendaraan?">
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
        @endforeach
    @endif
@endsection
