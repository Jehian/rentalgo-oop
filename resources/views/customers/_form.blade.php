@php($customer = $customer ?? null)

<x-card title="Data Pelanggan" subtitle="Lengkapi identitas pelanggan sesuai dokumen yang berlaku.">
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <x-input
            name="name"
            label="Nama Lengkap"
            :value="$customer?->name"
            placeholder="Contoh: Budi Santoso"
            required
        />

        <x-input
            name="email"
            type="email"
            label="Email (opsional)"
            :value="$customer?->email"
            placeholder="nama@email.com"
            hint="Akun portal pelanggan tetap dibuat melalui registrasi mandiri."
        />

        <x-input
            name="phone"
            label="Nomor Telepon"
            :value="$customer?->phone"
            placeholder="08xxxxxxxxxx"
            required
        />

        <x-input
            name="identity_number"
            label="NIK / Nomor KTP"
            :value="$customer?->identity_number"
            placeholder="16 digit NIK"
            required
        />

        <x-input
            name="license_number"
            label="Nomor SIM"
            :value="$customer?->license_number"
            placeholder="Nomor SIM"
            required
        />

        <div class="md:col-span-2">
            <label for="address" class="block text-sm font-medium text-slate-700">Alamat</label>
            <textarea
                id="address"
                name="address"
                rows="3"
                placeholder="Alamat lengkap pelanggan"
                class="mt-1.5 block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-0 {{ $errors->has('address') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
            >{{ old('address', $customer?->address) }}</textarea>

            @error('address')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</x-card>
