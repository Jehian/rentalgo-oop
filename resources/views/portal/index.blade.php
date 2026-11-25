@extends('layouts.public')

@section('title', 'Katalog Kendaraan')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Katalog Kendaraan</h1>
                <p class="text-sm text-slate-500">
                    {{ number_format($stats['available'], 0, ',', '.') }} dari {{ number_format($stats['total'], 0, ',', '.') }} kendaraan tersedia untuk disewa.
                </p>
            </div>
        </div>

        <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-white p-4 md:grid-cols-5">
            <div class="md:col-span-2">
                <label for="search" class="mb-1 block text-xs font-medium text-slate-600">Cari</label>
                <input
                    id="search"
                    name="search"
                    type="text"
                    value="{{ $filters['search'] }}"
                    placeholder="Merek, model, atau nomor polisi"
                    class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                >
            </div>

            <div>
                <label for="type" class="mb-1 block text-xs font-medium text-slate-600">Tipe</label>
                <select id="type" name="type" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Tipe</option>
                    @foreach ($vehicleTypes as $type)
                        <option value="{{ $type->value }}" @selected($filters['type'] === $type->value)>{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="mb-1 block text-xs font-medium text-slate-600">Status</label>
                <select id="status" name="status" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    @foreach ($vehicleStatuses as $status)
                        <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sort" class="mb-1 block text-xs font-medium text-slate-600">Urutkan</label>
                <select id="sort" name="sort" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none">
                    <option value="newest" @selected($filters['sort'] === 'newest')>Terbaru</option>
                    <option value="rate_asc" @selected($filters['sort'] === 'rate_asc')>Tarif Termurah</option>
                    <option value="rate_desc" @selected($filters['sort'] === 'rate_desc')>Tarif Termahal</option>
                </select>
            </div>

            <div class="flex items-center gap-2 md:col-span-5">
                <x-button type="submit" variant="primary" size="sm">Filter</x-button>
                @if ($filters['search'] !== '' || $filters['type'] !== '' || $filters['status'] !== '' || $filters['sort'] !== 'newest')
                    <x-button :href="route('home')" variant="secondary" size="sm">Reset</x-button>
                @endif
            </div>
        </form>

        @if ($vehicles->isEmpty())
            <div class="rounded-xl border border-slate-200 bg-white p-8 text-center">
                <p class="text-sm font-medium text-slate-700">Tidak ada kendaraan ditemukan.</p>
                <div class="mt-3">
                    <x-button :href="route('home')" variant="secondary" size="sm">Reset Filter</x-button>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($vehicles as $vehicle)
                    <article class="flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <div class="aspect-[16/10] bg-slate-100">
                            @if ($vehicle->image_path)
                                <img
                                    src="{{ asset('storage/'.$vehicle->image_path) }}"
                                    alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center text-xs font-medium text-slate-400">
                                    {{ $vehicle->type->label() }}
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col justify-between p-4">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-medium text-slate-500">{{ $vehicle->type->label() }} · {{ $vehicle->year }}</span>
                                    <x-status-badge :status="$vehicle->status" />
                                </div>

                                <h2 class="mt-1.5 text-base font-semibold text-slate-900">{{ $vehicle->brand }} {{ $vehicle->model }}</h2>
                                <p class="text-xs text-slate-500">
                                    {{ $vehicle->plate_number }}
                                    @if ($vehicle->type === \App\Enums\VehicleType::Car)
                                        · {{ $vehicle->seat_count }} kursi · {{ ucfirst((string) $vehicle->transmission) }}
                                    @else
                                        · {{ $vehicle->engine_cc }} cc
                                    @endif
                                </p>
                            </div>

                            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                                <p class="text-sm font-bold text-slate-900">
                                    Rp {{ number_format((float) $vehicle->daily_rate, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-slate-500">/hari</span>
                                </p>

                                <x-button :href="route('portal.vehicles.show', $vehicle)" :variant="$vehicle->isAvailable() ? 'primary' : 'secondary'" size="sm">
                                    {{ $vehicle->isAvailable() ? 'Detail & Sewa' : 'Detail' }}
                                </x-button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <x-pagination :paginator="$vehicles" />
        @endif
    </div>
@endsection
