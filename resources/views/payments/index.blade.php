@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pembayaran</h1>
                <p class="text-sm text-slate-500">Catat pembayaran rental, kelola pembayaran menunggu, dan tandai lunas atau gagal.</p>
            </div>

            <x-button :href="route('payments.create')">+ Catat Pembayaran</x-button>
        </div>

        <x-card>
            <form method="GET" action="{{ route('payments.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-5">
                <div class="md:col-span-2">
                    <label for="search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Cari</label>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ $filters['search'] }}"
                        placeholder="Kode pembayaran, kode rental, atau nama pelanggan"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Status</label>
                    <select id="status" name="status" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">Semua Status</option>
                        @foreach ($paymentStatuses as $status)
                            <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="method" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Metode</label>
                    <select id="method" name="method" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">Semua Metode</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->value }}" @selected($filters['method'] === $method->value)>{{ $method->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutkan</label>
                    <select id="sort" name="sort" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Terbaru</option>
                        <option value="oldest" @selected($filters['sort'] === 'oldest')>Terlama</option>
                        <option value="amount_desc" @selected($filters['sort'] === 'amount_desc')>Nominal Terbesar</option>
                        <option value="amount_asc" @selected($filters['sort'] === 'amount_asc')>Nominal Terkecil</option>
                    </select>
                </div>

                <div class="flex items-end gap-2 md:col-span-5">
                    <x-button type="submit" variant="secondary" size="sm">Filter</x-button>
                    @if ($filters['search'] !== '' || $filters['status'] !== '' || $filters['method'] !== '' || $filters['sort'] !== 'newest')
                        <x-button :href="route('payments.index')" variant="ghost" size="sm">Reset</x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <x-card :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kode Pembayaran</th>
                            <th scope="col" class="px-5 py-3">Rental</th>
                            <th scope="col" class="px-5 py-3">Pelanggan</th>
                            <th scope="col" class="px-5 py-3">Metode</th>
                            <th scope="col" class="px-5 py-3 text-right">Nominal</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($payments as $payment)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="whitespace-nowrap px-5 py-3">
                                    <a href="{{ route('payments.show', $payment) }}" class="font-medium text-slate-900 hover:text-indigo-600">
                                        {{ $payment->payment_code }}
                                    </a>
                                    <p class="text-xs text-slate-500">
                                        {{ $payment->paid_at ? 'Lunas '.$payment->paid_at->format('d/m/Y H:i') : 'Dibuat '.$payment->created_at?->format('d/m/Y H:i') }}
                                    </p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    @if ($payment->rental)
                                        <a href="{{ route('rentals.show', $payment->rental) }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            {{ $payment->rental->rental_code }}
                                        </a>
                                        <p class="text-xs text-slate-500">
                                            {{ $payment->rental->vehicle?->plate_number ?? '—' }}
                                        </p>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-700">
                                    {{ $payment->rental?->customer?->name ?? 'Pelanggan dihapus' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-slate-700">{{ $payment->method->label() }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-right font-medium text-slate-900">
                                    Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <x-status-badge :status="$payment->status" />
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button :href="route('payments.show', $payment)" variant="secondary" size="sm">Detail</x-button>

                                        @if ($payment->status === \App\Enums\PaymentStatus::Pending)
                                            <x-button :href="route('payments.edit', $payment)" variant="ghost" size="sm">Edit</x-button>
                                            <x-button
                                                type="button"
                                                variant="danger"
                                                size="sm"
                                                data-modal-open="delete-payment-{{ $payment->id }}"
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
                                    <p class="text-sm font-medium text-slate-600">Belum ada data pembayaran.</p>
                                    <p class="mt-1 text-xs text-slate-400">Catat pembayaran pertama untuk transaksi rental yang ada.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-pagination :paginator="$payments" />
    </div>

    @foreach ($payments as $payment)
        @if ($payment->status === \App\Enums\PaymentStatus::Pending)
            <x-modal id="delete-payment-{{ $payment->id }}" title="Hapus Pembayaran?">
                <p class="text-sm text-slate-600">
                    Pembayaran <span class="font-semibold text-slate-900">{{ $payment->payment_code }}</span> senilai
                    <span class="font-semibold text-slate-900">Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</span>
                    yang masih berstatus menunggu akan dihapus.
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
    @endforeach
@endsection
