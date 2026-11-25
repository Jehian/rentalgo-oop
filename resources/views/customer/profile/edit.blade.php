@extends('layouts.public')

@section('title', 'Profil Saya')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Profil Pelanggan</h1>
            <p class="text-sm text-slate-500">Perbarui data identitas dan kontak Anda untuk keperluan penyewaan.</p>
        </div>

        <x-card>
            <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm">
                    <p class="text-xs font-medium text-slate-500">Email Akun</p>
                    <p class="font-medium text-slate-900">{{ $customer->email }}</p>
                </div>

                <x-input
                    name="name"
                    label="Nama Lengkap"
                    :value="$customer->name"
                    required
                />

                <x-input
                    name="phone"
                    label="Nomor Telepon"
                    :value="$customer->phone"
                    required
                />

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-input
                        name="identity_number"
                        label="NIK / Nomor KTP"
                        :value="$customer->identity_number"
                        required
                    />

                    <x-input
                        name="license_number"
                        label="Nomor SIM"
                        :value="$customer->license_number"
                        required
                    />
                </div>

                <div class="space-y-1.5">
                    <label for="address" class="block text-sm font-medium text-slate-700">Alamat</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-0 {{ $errors->has('address') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                    >{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-button :href="route('customer.rentals.index')" variant="secondary">Lihat Pesanan</x-button>
                    <x-button type="submit" variant="primary">Simpan Perubahan</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
