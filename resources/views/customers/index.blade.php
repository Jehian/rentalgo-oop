@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pelanggan</h1>
                <p class="text-sm text-slate-500">Kelola data pelanggan dan lihat riwayat rentalnya.</p>
            </div>

            <x-button :href="route('customers.create')">+ Tambah Pelanggan</x-button>
        </div>

        <x-card>
            <form method="GET" action="{{ route('customers.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-4">
                <div class="md:col-span-2">
                    <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Cari</label>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ $filters['search'] }}"
                        placeholder="Nama, email, telepon, NIK, atau SIM"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="sort" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutkan</label>
                    <select id="sort" name="sort" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Terbaru</option>
                        <option value="oldest" @selected($filters['sort'] === 'oldest')>Terlama</option>
                        <option value="name_asc" @selected($filters['sort'] === 'name_asc')>Nama A–Z</option>
                        <option value="name_desc" @selected($filters['sort'] === 'name_desc')>Nama Z–A</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <x-button type="submit" variant="secondary" size="sm">Filter</x-button>
                    @if ($filters['search'] !== '' || $filters['sort'] !== 'newest')
                        <x-button :href="route('customers.index')" variant="ghost" size="sm">Reset</x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <x-card :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Pelanggan</th>
                            <th scope="col" class="px-5 py-3">Kontak</th>
                            <th scope="col" class="px-5 py-3">Identitas</th>
                            <th scope="col" class="px-5 py-3">Rental Aktif</th>
                            <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($customers as $customer)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-3">
                                    <a href="{{ route('customers.show', $customer) }}" class="font-medium text-slate-900 hover:text-indigo-600">
                                        {{ $customer->name }}
                                    </a>
                                    <p class="text-xs text-slate-500">{{ $customer->email ?? 'Tanpa email' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-700">{{ $customer->phone }}</td>
                                <td class="px-5 py-3 text-slate-600">
                                    <p>NIK: {{ $customer->identity_number }}</p>
                                    <p class="text-xs text-slate-500">SIM: {{ $customer->license_number }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    @if ($customer->active_rentals_count > 0)
                                        <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-600/20">
                                            {{ $customer->active_rentals_count }} aktif
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button :href="route('customers.show', $customer)" variant="secondary" size="sm">Detail</x-button>
                                        <x-button :href="route('customers.edit', $customer)" variant="ghost" size="sm">Edit</x-button>

                                        @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                                            <x-button
                                                type="button"
                                                variant="danger"
                                                size="sm"
                                                data-modal-open="delete-customer-{{ $customer->id }}"
                                            >
                                                Hapus
                                            </x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600">Belum ada pelanggan.</p>
                                    <p class="mt-1 text-xs text-slate-400">Tambahkan pelanggan pertama untuk mulai mencatat rental.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-pagination :paginator="$customers" />
    </div>

    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
        @foreach ($customers as $customer)
            <x-modal id="delete-customer-{{ $customer->id }}" title="Hapus Pelanggan?">
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
        @endforeach
    @endif
@endsection
